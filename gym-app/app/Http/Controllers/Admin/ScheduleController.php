<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('admin.schedules.index', [
            'schedules' => Schedule::with(['member.user', 'trainer.user'])->orderBy('date')->orderBy('time')->get(),
            'members' => Member::with('user')->get(),
            'trainers' => Trainer::with('user')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.schedules.create', [
            'members' => Member::with('user')->get(),
            'trainers' => Trainer::with('user')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'trainer_id' => ['required', 'exists:trainers,id'],
            'date' => ['required', 'date'],
            'time' => ['required'],
            'status' => ['required', 'string', 'max:20'],
        ]);

        Schedule::create($validated);

        return back()->with('success', 'Da tao lich tap.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required'],
            'status' => ['required', 'string', 'max:20'],
        ]);

        $schedule->update($validated);

        return back()->with('success', 'Da cap nhat lich tap.');
    }

    public function edit(Schedule $schedule)
    {
        $schedule->load(['member.user', 'trainer.user']);

        return view('admin.schedules.edit', [
            'schedule' => $schedule,
            'members' => Member::with('user')->get(),
            'trainers' => Trainer::with('user')->get(),
        ]);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Da xoa lich tap.');
    }
}
