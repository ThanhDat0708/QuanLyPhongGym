<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Trainer;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now();
        $search = trim((string) $request->query('q', ''));

        $dashboardRegistrations = Registration::with([
            'member.user',
            'gymPackage',
            'preferredTrainer.user',
            'payment',
        ])
            ->latest();

        if ($search !== '') {
            $dashboardRegistrations->where(function ($query) use ($search) {
                $query->where('status', 'like', "%{$search}%")
                    ->orWhereHas('member.user', function ($memberQuery) use ($search) {
                        $memberQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('gymPackage', function ($packageQuery) use ($search) {
                        $packageQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('preferredTrainer.user', function ($trainerQuery) use ($search) {
                        $trainerQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('payment', function ($paymentQuery) use ($search) {
                        $paymentQuery->where('status', 'like', "%{$search}%")
                            ->orWhere('method', 'like', "%{$search}%");
                    });
            });
        }

        return view('admin.dashboard', [
            'membersCount' => Member::count(),
            'trainersCount' => Trainer::count(),
            'registrationsCount' => Registration::count(),
            'revenue' => Payment::where('status', 'paid')->sum('amount'),
            'dailyRevenue' => Payment::where('status', 'paid')
                ->whereDate('payment_date', $today->toDateString())
                ->sum('amount'),
            'monthlyRevenue' => Payment::where('status', 'paid')
                ->whereYear('payment_date', $today->year)
                ->whereMonth('payment_date', $today->month)
                ->sum('amount'),
            'yearlyRevenue' => Payment::where('status', 'paid')
                ->whereYear('payment_date', $today->year)
                ->sum('amount'),
            'dailyRegistrations' => Registration::whereDate('created_at', $today->toDateString())->count(),
            'monthlyRegistrations' => Registration::whereYear('created_at', $today->year)
                ->whereMonth('created_at', $today->month)
                ->count(),
            'yearlyRegistrations' => Registration::whereYear('created_at', $today->year)->count(),
            'search' => $search,
            'dashboardRegistrations' => $dashboardRegistrations->paginate(8)->withQueryString(),
        ]);
    }
}
