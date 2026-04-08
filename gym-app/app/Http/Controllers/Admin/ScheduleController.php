<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $schedules = Schedule::with(['member.user', 'trainer.user'])->orderBy('date')->orderBy('time');

        if ($search !== '') {
            $schedules->where(function ($query) use ($search) {
                $query->where('date', 'like', "%{$search}%")
                    ->orWhere('time', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('member.user', function ($memberQuery) use ($search) {
                        $memberQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('trainer.user', function ($trainerQuery) use ($search) {
                        $trainerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        return view('admin.schedules.index', [
            'schedules' => $schedules->get(),
            'members' => Member::with('user')->get(),
            'trainers' => Trainer::with('user')->get(),
            'search' => $search,
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
            'status' => ['required', 'in:pending,done,cancel'],
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Da tao lich tap.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required'],
            'status' => ['required', 'in:pending,done,cancel'],
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Da cap nhat lich tap.');
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
