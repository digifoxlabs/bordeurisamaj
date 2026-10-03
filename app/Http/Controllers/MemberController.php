<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function rules(?Member $member = null): array
    {
        return [
            'name' => ['required', 'string', 'max:160'],
            'mobile' => ['required', 'digits:10', 'unique:members,mobile'.($member ? ','.$member->id : '')],
            'whatsapp' => ['nullable', 'digits:10'],
            'email' => ['nullable', 'email', 'max:190'],
            'role' => ['required', 'in:Burha,Deka,Hota,Bidhipathak'],
            'father_name' => ['required', 'string', 'max:160'],
            'grandfather_name' => ['required', 'string', 'max:160'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'address' => ['nullable', 'string', 'max:2000'],
            'occupation' => ['nullable', 'string', 'max:190'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'documents' => ['nullable', 'array', 'max:2'],
            'documents.*.title' => ['required_with:documents.*.file', 'nullable', 'string', 'max:160'],
            'documents.*.file' => ['nullable', 'file', 'max:10240'],
        ];
    }

    public function home() { return view('frontend.home'); }
    public function create() { return view('frontend.form'); }

    public function editDraft(Request $request)
    {
        return redirect()->route('membership.create')->withInput($request->except('_token'));
    }

    public function checkMobile(Request $request)
    {
        $request->validate(['mobile' => ['required', 'digits:10']]);
        return response()->json(['available' => ! Member::where('mobile', $request->mobile)->exists()]);
    }

    public function preview(Request $request)
    {
        $data = $request->validate($this->rules());
        $docs = [];
        foreach ($request->file('documents', []) as $index => $item) {
            if (empty($item['file'])) continue;
            $path = $item['file']->store('pending-member-documents', 'local');
            $docs[] = ['path' => $path, 'title' => $item['title'] ?? '', 'original_name' => $item['file']->getClientOriginalName()];
        }
        session(['pending_member_documents' => $docs]);
        $data['document_names'] = array_map(fn ($doc) => $doc['title'] ?: $doc['original_name'], $docs);
        if ($request->filled('photo_preview')) {
            $request->validate(['photo_preview' => ['string', 'max:7000000']]);
            $data['photo_preview'] = $request->input('photo_preview');
        } elseif ($request->hasFile('photo')) {
            $data['photo_preview'] = 'data:'.$request->file('photo')->getMimeType().';base64,'.base64_encode(file_get_contents($request->file('photo')->getRealPath()));
        }
        return view('frontend.preview', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return redirect()->route('membership.create')->withErrors($validator)->withInput($request->except('photo_preview', 'photo'));
        }
        $data = $validator->validated();
        $pendingDocs = session()->pull('pending_member_documents', []);
        if ($request->filled('photo_preview')) {
            $request->validate(['photo_preview' => ['string', 'max:7000000']]);
            $parts = explode(',', $request->input('photo_preview'), 2);
            $bytes = count($parts) === 2 ? base64_decode($parts[1], true) : false;
            $mime = str_starts_with($parts[0] ?? '', 'data:image/jpeg;') ? 'jpg' : (str_starts_with($parts[0] ?? '', 'data:image/png;') ? 'png' : (str_starts_with($parts[0] ?? '', 'data:image/webp;') ? 'webp' : null));
            if ($bytes !== false && $mime && strlen($bytes) <= 5 * 1024 * 1024 && @getimagesizefromstring($bytes)) {
                $path = 'members/'.\Illuminate\Support\Str::uuid().'.'.$mime;
                Storage::disk('public')->put($path, $bytes);
                $data['photo'] = $path;
            }
        } elseif ($request->hasFile('photo')) $data['photo'] = $request->file('photo')->store('members', 'public');
        $member = Member::create($data);
        foreach ($pendingDocs as $doc) {
            $target = 'member-documents/'.$member->id.'/'.basename($doc['path']);
            Storage::disk('local')->move($doc['path'], $target);
            $member->documents()->create(['title' => $doc['title'] ?: $doc['original_name'], 'original_name' => $doc['original_name'], 'path' => $target]);
        }
        return redirect()->route('success', $member);
    }

    public function success(Member $member) { return view('frontend.success', compact('member')); }
}
