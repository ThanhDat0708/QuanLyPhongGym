<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Trainer;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'membersCount' => Member::count(),
            'trainersCount' => Trainer::count(),
            'registrationsCount' => Registration::count(),
            'revenue' => Payment::where('status', 'paid')->sum('amount'),
        ]);
    }
}
