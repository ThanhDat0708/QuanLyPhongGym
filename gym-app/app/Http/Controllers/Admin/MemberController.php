<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $members = Member::with('user')->latest();

        if ($search !== '') {
            $members->where(function ($query) use ($search) {
                $query->where('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        return view('admin.members.index', [
            'members' => $members->get(),
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
        ]);

        $user->member()->create([
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Da tao hoi vien.');
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$member->user_id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:20'],
        ]);

        $member->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $member->update([
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Da cap nhat hoi vien.');
    }

    public function edit(Member $member)
    {
        $member->load('user');

        return view('admin.members.edit', compact('member'));
    }

    public function destroy(Member $member)
    {
        $member->user()->delete();

        return back()->with('success', 'Da xoa hoi vien.');
    }
}
