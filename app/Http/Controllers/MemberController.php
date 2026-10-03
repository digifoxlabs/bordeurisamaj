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
            'role' => ['nullable', 'in:Burha,Deka,Hota,Bidhipathak'],
            'father_name' => ['nullable', 'string', 'max:160'],
            'grandfather_name' => ['nullable', 'string', 'max:160'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'address' => ['nullable', 'string', 'max:2000'],
            'occupation' => ['nullable', 'string', 'max:190'],
            'photo' => ['nullable', 'image', 'max:5120'],
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
        return redirect()->route('success', $member);
    }

    public function success(Member $member) { return view('frontend.success', compact('member')); }
}
