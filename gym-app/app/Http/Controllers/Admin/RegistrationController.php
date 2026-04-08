<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('admin.registrations.index', [
            'registrations' => Registration::with(['member.user', 'gymPackage', 'payment'])->latest()->get(),
            'members' => Member::with('user')->get(),
            'packages' => GymPackage::all(),
        ]);
    }

    public function create()
    {
        return view('admin.registrations.create', [
            'members' => Member::with('user')->get(),
            'packages' => GymPackage::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'gym_package_id' => ['required', 'exists:gym_packages,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'string', 'max:20'],
        ]);

        Registration::create($validated);

        return back()->with('success', 'Da tao dang ky goi tap.');
    }

    public function update(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'string', 'max:20'],
        ]);

        $registration->update($validated);

        return back()->with('success', 'Da cap nhat dang ky.');
    }

    public function edit(Registration $registration)
    {
        $registration->load(['member.user', 'gymPackage']);

        return view('admin.registrations.edit', [
            'registration' => $registration,
            'members' => Member::with('user')->get(),
            'packages' => GymPackage::all(),
        ]);
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();

        return back()->with('success', 'Da xoa dang ky.');
    }
}
