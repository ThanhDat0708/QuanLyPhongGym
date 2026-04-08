<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $registrations = Registration::with(['member.user', 'gymPackage', 'payment', 'preferredTrainer.user'])->latest();

        if ($search !== '') {
            $registrations->where(function ($query) use ($search) {
                $query->where('status', 'like', "%{$search}%")
                    ->orWhere('start_date', 'like', "%{$search}%")
                    ->orWhere('end_date', 'like', "%{$search}%")
                    ->orWhereHas('member.user', function ($memberQuery) use ($search) {
                        $memberQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('gymPackage', function ($packageQuery) use ($search) {
                        $packageQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('preferredTrainer.user', function ($trainerQuery) use ($search) {
                        $trainerQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return view('admin.registrations.index', [
            'registrations' => $registrations->get(),
            'members' => Member::with('user')->get(),
            'packages' => GymPackage::all(),
            'trainers' => Trainer::with('user')->where('status', 'active')->get(),
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.registrations.create', [
            'members' => Member::with('user')->get(),
            'packages' => GymPackage::all(),
            'trainers' => Trainer::with('user')->where('status', 'active')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'gym_package_id' => ['required', 'exists:gym_packages,id'],
            'preferred_trainer_id' => ['nullable', 'exists:trainers,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:pending,paid,active,done,cancel'],
        ]);

        $registration = Registration::create($validated);

        $createdSchedule = $this->autoCreateScheduleOnConfirmation($registration);

        $message = 'Da tao dang ky goi tap.';
        if ($createdSchedule) {
            $trainerName = $createdSchedule->trainer?->user?->name ?? 'PT';
            $message .= ' He thong da tu dong xep lich voi ' . $trainerName
                . ' vao ' . $createdSchedule->date->format('d/m/Y')
                . ' luc ' . $createdSchedule->time . '.';
        }

        return redirect()->route('admin.registrations.index')->with('success', $message);
    }

    public function update(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'preferred_trainer_id' => ['nullable', 'exists:trainers,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:pending,paid,active,done,cancel'],
        ]);

        $registration->update($validated);

        $createdSchedule = $this->autoCreateScheduleOnConfirmation($registration);

        $message = 'Da cap nhat dang ky.';
        if ($createdSchedule) {
            $trainerName = $createdSchedule->trainer?->user?->name ?? 'PT';
            $message .= ' He thong da tu dong xep lich voi ' . $trainerName
                . ' vao ' . $createdSchedule->date->format('d/m/Y')
                . ' luc ' . $createdSchedule->time . '.';
        }

        return redirect()->route('admin.registrations.index')->with('success', $message);
    }

    public function edit(Registration $registration)
    {
        $registration->load(['member.user', 'gymPackage', 'preferredTrainer.user']);

        return view('admin.registrations.edit', [
            'registration' => $registration,
            'members' => Member::with('user')->get(),
            'packages' => GymPackage::all(),
            'trainers' => Trainer::with('user')->where('status', 'active')->get(),
        ]);
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();

        return back()->with('success', 'Da xoa dang ky.');
    }

    private function autoCreateScheduleOnConfirmation(Registration $registration): ?Schedule
    {
        if (! in_array($registration->status, ['paid', 'active'], true)) {
            return null;
        }

        if (! $registration->preferred_trainer_id) {
            return null;
        }

        $trainer = Trainer::with('user')
            ->where('id', $registration->preferred_trainer_id)
            ->where('status', 'active')
            ->first();

        if (! $trainer) {
            return null;
        }

        $hasExisting = Schedule::where('member_id', $registration->member_id)
            ->where('trainer_id', $trainer->id)
            ->whereDate('date', '>=', now()->toDateString())
            ->where('status', '!=', 'cancel')
            ->exists();

        if ($hasExisting) {
            return null;
        }

        $startDate = $registration->start_date->isPast()
            ? now()->startOfDay()
            : $registration->start_date->copy();

        $timeSlots = ['06:00', '07:00', '08:00', '09:00', '17:00', '18:00', '19:00'];

        for ($dayOffset = 0; $dayOffset < 14; $dayOffset++) {
            $date = $startDate->copy()->addDays($dayOffset)->toDateString();

            foreach ($timeSlots as $time) {
                $trainerBusy = Schedule::where('trainer_id', $trainer->id)
                    ->whereDate('date', $date)
                    ->where('time', $time)
                    ->where('status', '!=', 'cancel')
                    ->exists();

                if ($trainerBusy) {
                    continue;
                }

                $schedule = Schedule::create([
                    'member_id' => $registration->member_id,
                    'trainer_id' => $trainer->id,
                    'date' => $date,
                    'time' => $time,
                    'status' => 'pending',
                ]);

                $schedule->setRelation('trainer', $trainer);

                return $schedule;
            }
        }

        return null;
    }
}
