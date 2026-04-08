<?php

namespace App\Http\Controllers;

use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class RegistrationController extends Controller
{
    public function create(Request $request, GymPackage $gymPackage)
    {
        $member = Member::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['status' => 'active']
        );

        if (! $this->hasCompletedPersonalInfo($member)) {
            return redirect()->route('site.personal-info')->withErrors([
                'profile' => 'Vui lòng cập nhật đầy đủ thông tin cá nhân trước khi đăng ký gói tập.',
            ]);
        }

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

        if (! $this->hasCompletedPersonalInfo($member)) {
            return redirect()->route('site.personal-info')->withErrors([
                'profile' => 'Vui lòng cập nhật đầy đủ thông tin cá nhân trước khi đăng ký gói tập.',
            ]);
        }

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
            'trainer_id' => ['nullable', 'exists:trainers,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required'],
        ]);

        $trainerId = $validated['trainer_id'] ?? $registration->preferred_trainer_id;
        if (! $trainerId) {
            return back()->withErrors(['trainer_id' => 'Vui lòng chọn huấn luyện viên.']);
        }

        $trainer = Trainer::where('id', $trainerId)
            ->where('status', 'active')
            ->first();

        if (! $trainer) {
            return back()->withErrors(['trainer_id' => 'Huấn luyện viên không khả dụng.']);
        }

        $isBusy = Schedule::where('trainer_id', $trainer->id)
            ->whereDate('date', $validated['date'])
            ->where('time', $validated['time'])
            ->where('status', '!=', 'cancel')
            ->exists();

        if ($isBusy) {
            return back()->withErrors(['trainer_id' => 'Huấn luyện viên này đang bận ở khung giờ bạn chọn. Vui lòng chọn PT khác.']);
        }

        Schedule::create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Đã tạo lịch tập với huấn luyện viên. Vui lòng chờ xác nhận.');
    }

    public function availableTrainers(Request $request, Registration $registration): JsonResponse
    {
        $member = $request->user()->member;
        abort_if(! $member || $registration->member_id !== $member->id, 403);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required'],
        ]);

        $trainers = Trainer::with('user')
            ->where('status', 'active')
            ->whereDoesntHave('schedules', function ($query) use ($validated) {
                $query->whereDate('date', $validated['date'])
                    ->where('time', $validated['time'])
                    ->where('status', '!=', 'cancel');
            })
            ->orderBy('id')
            ->get()
            ->map(fn ($trainer) => [
                'id' => $trainer->id,
                'name' => $trainer->user->name,
                'specialty' => $trainer->specialty,
            ])
            ->values();

        return response()->json($trainers);
    }

    public function pay(Request $request, Payment $payment)
    {
        $member = $request->user()->member;

        abort_if(! $member || $payment->registration->member_id !== $member->id, 403);

        if ($payment->status !== 'pending') {
            return back()->withErrors(['payment' => 'Hóa đơn này không thể thanh toán thêm.']);
        }

        $payment->update([
            'status' => 'paid',
            'payment_date' => now()->toDateString(),
        ]);

        if ($payment->registration->status === 'pending') {
            $payment->registration->update(['status' => 'paid']);
        }

        return back()->with('success', 'Thanh toán hóa đơn thành công.');
    }

    private function hasCompletedPersonalInfo(Member $member): bool
    {
        return ! empty($member->phone)
            && ! empty($member->address)
            && ! is_null($member->height)
            && ! is_null($member->weight);
    }
}
