<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function login() { return view('admin.login'); }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        if (Auth::attempt($credentials, $request->boolean('remember')) && Auth::user()->is_admin) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }
        Auth::logout();
        return back()->withErrors(['email' => 'Those credentials do not match our records.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function dashboard(Request $request)
    {
        $query = Member::query()->latest();
        if ($search = $request->string('q')->trim()->toString()) $query->where(fn ($q) => $q->where('name', 'like', "%$search%")->orWhere('mobile', 'like', "%$search%"));
        $pageSize = $request->query('per_page', '10');
        if (! in_array((string) $pageSize, ['10', '50', '100', 'all'], true)) $pageSize = '10';
        $perPage = $pageSize === 'all' ? max($query->count(), 1) : (int) $pageSize;

        return view('admin.dashboard', [
            'members' => $query->paginate($perPage)->withQueryString(),
            'pageSize' => (string) $pageSize,
            'total' => Member::count(),
            'today' => Member::whereDate('created_at', today())->count(),
        ]);
    }

    public function show(Member $member) { return view('admin.member', compact('member')); }
    public function edit(Member $member) { return view('admin.edit', compact('member')); }

    public function update(Request $request, Member $member)
    {
        $data = $request->validate((new MemberController)->rules($member));
        unset($data['documents']);
        if ($request->hasFile('photo')) {
            if ($member->photo) Storage::disk('public')->delete($member->photo);
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }
        $member->update($data);
        foreach ($request->file('documents', []) as $item) {
            if (empty($item['file'])) continue;
            $path = $item['file']->store('member-documents/'.$member->id, 'local');
            $member->documents()->create(['title' => $item['title'] ?: $item['file']->getClientOriginalName(), 'original_name' => $item['file']->getClientOriginalName(), 'path' => $path]);
        }
        return redirect()->route('admin.members.show', $member)->with('status', 'Member record updated.');
    }

    public function downloadMemberDocument(Member $member, \App\Models\MemberDocument $document)
    {
        abort_unless($document->member_id === $member->id && Storage::disk('local')->exists($document->path), 404);
        return Storage::disk('local')->download($document->path, $document->original_name);
    }

    public function deleteMemberDocument(Member $member, \App\Models\MemberDocument $document)
    {
        abort_unless($document->member_id === $member->id, 404);
        Storage::disk('local')->delete($document->path);
        $document->delete();
        return back()->with('status', 'Member document deleted.');
    }

    public function destroy(Member $member)
    {
        if ($member->photo) Storage::disk('public')->delete($member->photo);
        foreach ($member->documents as $document) Storage::disk('local')->delete($document->path);
        $member->delete();
        return redirect()->route('admin.dashboard')->with('status', 'Member record deleted.');
    }

    public function profile() { return view('admin.profile'); }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate(['name' => ['required', 'string', 'max:160'], 'email' => ['required', 'email', 'max:190', 'unique:users,email,'.$user->id], 'password' => ['nullable', 'confirmed', 'min:8']]);
        $user->name = $data['name']; $user->email = $data['email'];
        if (! empty($data['password'])) $user->password = Hash::make($data['password']);
        $user->save();
        return back()->with('status', 'Profile saved successfully.');
    }
}
