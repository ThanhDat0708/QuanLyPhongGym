<?php

namespace App\Http\Controllers;

use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RegistrationController extends Controller
{
    public function create(Request $request, GymPackage $gymPackage)
    {
        return view('site.package-register-confirm', [
            'gymPackage' => $gymPackage,
            'today' => now()->toDateString(),
            'trainers' => Trainer::with('user')
                ->where('status', 'active')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function store(Request $request, GymPackage $gymPackage)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'trainer_id' => ['nullable', 'exists:trainers,id'],
            'confirm_registration' => ['required', 'accepted'],
        ]);

        $member = Member::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['status' => 'active']
        );

        $startDate = $validated['start_date'] ?? now()->toDateString();

        $preferredTrainerId = null;
        if (! empty($validated['trainer_id'])) {
            $preferredTrainer = Trainer::where('id', $validated['trainer_id'])
                ->where('status', 'active')
                ->first();

            if (! $preferredTrainer) {
                return back()->withErrors(['trainer_id' => 'Huấn luyện viên đã chọn hiện không khả dụng.'])->withInput();
            }

            $preferredTrainerId = $preferredTrainer->id;
        }

        $registrationData = [
            'member_id' => $member->id,
            'gym_package_id' => $gymPackage->id,
            'start_date' => $startDate,
            'end_date' => now()->parse($startDate)->addDays($gymPackage->duration)->toDateString(),
            'status' => 'pending',
        ];

        if (Schema::hasColumn('registrations', 'preferred_trainer_id')) {
            $registrationData['preferred_trainer_id'] = $preferredTrainerId;
        }

        $registration = Registration::create($registrationData);

        Payment::create([
            'registration_id' => $registration->id,
            'amount' => $gymPackage->price,
            'method' => 'invoice',
            'status' => 'pending',
        ]);

        return redirect()->route('site.registrations')->with('success', 'Đăng ký gói tập thành công. Đơn của bạn đang chờ admin xác nhận.');
    }

    public function cancel(Request $request, Registration $registration)
    {
        $member = $request->user()->member;

        abort_if(! $member || $registration->member_id !== $member->id, 403);

        if ($registration->status !== 'pending') {
            return back()->withErrors(['registration' => 'Chỉ có thể hủy khi đăng ký đang chờ xác nhận.']);
        }

        $registration->update(['status' => 'cancel']);

        if ($registration->payment && $registration->payment->status === 'pending') {
            $registration->payment->update(['status' => 'cancel']);
        }

        return back()->with('success', 'Đã hủy đăng ký gói tập đang chờ xác nhận.');
    }

    public function storeSchedule(Request $request, Registration $registration)
    {
        $member = $request->user()->member;

        abort_if(! $member || $registration->member_id !== $member->id, 403);

        if (! in_array($registration->status, ['paid', 'active'], true)) {
            return back()->withErrors(['schedule' => 'Đăng ký này không thể tạo lịch tập.']);
        }

        $validated = $request->validate([
            'trainer_id' => ['required', 'exists:trainers,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required'],
        ]);

        $trainer = Trainer::findOrFail($validated['trainer_id']);

        Schedule::create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Đã tạo lịch tập với huấn luyện viên. Vui lòng chờ xác nhận.');
    }
}
