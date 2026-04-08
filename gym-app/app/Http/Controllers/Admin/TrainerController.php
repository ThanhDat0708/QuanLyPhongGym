<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TrainerController extends Controller
{
    public function index()
    {
        return view('admin.trainers.index', [
            'trainers' => Trainer::with('user')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.trainers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'experience' => ['required', 'integer', 'min:0'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff',
        ]);

        $user->trainer()->create([
            'experience' => $validated['experience'],
            'specialty' => $validated['specialty'] ?? null,
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Da tao huan luyen vien.');
    }

    public function update(Request $request, Trainer $trainer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$trainer->user_id],
            'experience' => ['required', 'integer', 'min:0'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:20'],
        ]);

        $trainer->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $trainer->update([
            'experience' => $validated['experience'],
            'specialty' => $validated['specialty'] ?? null,
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Da cap nhat huan luyen vien.');
    }

    public function edit(Trainer $trainer)
    {
        $trainer->load('user');

        return view('admin.trainers.edit', compact('trainer'));
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->user()->delete();

        return back()->with('success', 'Da xoa huan luyen vien.');
    }
}
