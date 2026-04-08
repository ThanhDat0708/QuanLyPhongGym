<?php

namespace App\Http\Controllers;

use App\Models\GymPackage;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Review;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(Request $request)
    {
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
        $member = $request->user()->member;

        return view('site.dashboard', [
            'member' => $member,
            'registrationCount' => $member ? Registration::where('member_id', $member->id)->count() : 0,
            'scheduleCount' => $member ? Schedule::where('member_id', $member->id)->count() : 0,
            'pendingPayments' => $member
                ? Payment::whereHas('registration', fn ($q) => $q->where('member_id', $member->id))
                    ->where('status', 'pending')
                    ->count()
                : 0,
        ]);
    }

    public function registrations(Request $request)
    {
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
        $member = $request->user()->member;
        $payments = $member
            ? Payment::with('registration.gymPackage')
                ->whereHas('registration', fn ($q) => $q->where('member_id', $member->id))
                ->latest()
                ->get()
            : collect();

        return view('site.payments', compact('payments'));
    }
}
