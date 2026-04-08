<?php

namespace App\Http\Controllers;

use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Review;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    private function redirectAdminIfNeeded(Request $request)
    {
        if ($request->user()?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return null;
    }

    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $search = trim((string) $request->query('q', ''));

        $packagesQuery = GymPackage::query();

        if ($search !== '') {
            $packagesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return view('site.home', [
            'packages' => $packagesQuery->latest()->get(),
            'search' => $search,
            'trainers' => Trainer::with('user')->latest()->get(),
            'reviews' => Review::with(['user', 'trainer.user'])->latest()->limit(6)->get(),
        ]);
    }

    public function dashboard(Request $request)
    {
        if ($redirect = $this->redirectAdminIfNeeded($request)) {
            return $redirect;
        }

        if ($request->user()?->role === 'staff') {
            return redirect()->route('trainer.today-schedules');
        }

        $member = $request->user()->member;

        return view('site.dashboard', [
            'member' => $member,
        ]);
    }

    public function trainerTodaySchedules(Request $request)
    {
        if ($request->user()?->role !== 'staff') {
            return redirect()->route('dashboard');
        }

        $trainer = $request->user()->trainer;

        abort_if(! $trainer, 403);

        $today = now()->toDateString();

        $schedules = Schedule::with(['member.user'])
            ->where('trainer_id', $trainer->id)
            ->whereDate('date', $today)
            ->orderBy('time')
            ->get();

        $selectedRegistrations = Registration::with(['member.user', 'gymPackage', 'payment', 'preferredTrainer.user'])
            ->where('preferred_trainer_id', $trainer->id)
            ->whereIn('status', ['pending', 'paid', 'active'])
            ->latest()
            ->get();

        return view('site.trainer-today-schedules', [
            'trainer' => $trainer->load('user'),
            'schedules' => $schedules,
            'selectedRegistrations' => $selectedRegistrations,
            'today' => $today,
            'totalSessions' => $schedules->count(),
            'uniqueMembers' => $schedules->pluck('member_id')->unique()->count(),
            'selectedCount' => $selectedRegistrations->count(),
        ]);
    }

    public function trainerAutoSchedule(Request $request, Registration $registration)
    {
        if ($request->user()?->role !== 'staff') {
            return redirect()->route('dashboard');
        }

        $trainer = $request->user()->trainer;

        abort_if(! $trainer, 403);
        abort_if($registration->preferred_trainer_id !== $trainer->id, 403);

        $schedule = $this->createScheduleForTrainerRegistration($registration, $trainer);

        if (! $schedule) {
            return back()->withErrors(['schedule' => 'Chưa tìm được khung giờ trống để xếp lịch cho hội viên này.']);
        }

        return back()->with('success', 'Đã tự động xếp lịch cho ' . $registration->member?->user?->name . ' vào ' . $schedule->date->format('d/m/Y') . ' lúc ' . $schedule->time . '.');
    }

    public function personalInfo(Request $request)
    {
        if ($redirect = $this->redirectAdminIfNeeded($request)) {
            return $redirect;
        }

        $member = Member::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['status' => 'active']
        );

        return view('site.personal-info', compact('member'));
    }

    public function updatePersonalInfo(Request $request)
    {
        if ($redirect = $this->redirectAdminIfNeeded($request)) {
            return $redirect;
        }

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'height' => ['required', 'numeric', 'min:1', 'max:3'],
            'weight' => ['required', 'numeric', 'min:20', 'max:300'],
        ]);

        Member::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'status' => 'active',
            ]
        );

        return redirect()->route('site.registrations')->with('success', 'Đã cập nhật thông tin cá nhân thành công. Bạn có thể đăng ký gói tập.');
    }

    public function registrations(Request $request)
    {
        if ($redirect = $this->redirectAdminIfNeeded($request)) {
            return $redirect;
        }

        $member = $request->user()->member;
        $registrations = $member
            ? Registration::with(['gymPackage', 'payment', 'preferredTrainer.user'])
                ->where('member_id', $member->id)
                ->latest()
                ->get()
            : collect();

        $trainers = Trainer::with('user')
            ->where('status', 'active')
            ->orderBy('id')
            ->get();

        return view('site.registrations', compact('registrations', 'trainers'));
    }

    public function schedules(Request $request)
    {
        if ($redirect = $this->redirectAdminIfNeeded($request)) {
            return $redirect;
        }

        $member = $request->user()->member;
        $schedules = $member
            ? Schedule::with('trainer.user')
                ->where('member_id', $member->id)
                ->orderBy('date')
                ->orderBy('time')
                ->get()
            : collect();

        return view('site.schedules', compact('schedules'));
    }

    public function payments(Request $request)
    {
        if ($redirect = $this->redirectAdminIfNeeded($request)) {
            return $redirect;
        }

        $member = $request->user()->member;
        $payments = $member
            ? Payment::with('registration.gymPackage')
                ->whereHas('registration', fn ($q) => $q->where('member_id', $member->id))
                ->where('status', '!=', 'cancel')
                ->latest()
                ->get()
            : collect();

        return view('site.payments', compact('payments'));
    }

    public function paymentInvoice(Request $request, Payment $payment)
    {
        if ($redirect = $this->redirectAdminIfNeeded($request)) {
            return $redirect;
        }

        $member = $request->user()->member;

        $payment->load('registration.gymPackage', 'registration.member.user');

        abort_if(! $member || $payment->registration->member_id !== $member->id, 403);

        if ($payment->status === 'cancel') {
            return redirect()->route('site.payments')->withErrors(['payment' => 'Hóa đơn đã hủy không khả dụng.']);
        }

        return view('site.payment-invoice', compact('payment'));
    }

    private function createScheduleForTrainerRegistration(Registration $registration, Trainer $trainer): ?Schedule
    {
        if (! in_array($registration->status, ['paid', 'active'], true)) {
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
