<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $payments = Payment::with('registration.member.user', 'registration.gymPackage')->latest();

        if ($search !== '') {
            $invoiceNumber = null;
            if (preg_match('/^(HD-)?0*([0-9]+)$/i', $search, $matches)) {
                $invoiceNumber = (int) $matches[2];
            }

            $payments->where(function ($query) use ($search, $invoiceNumber) {
                $query->where('status', 'like', "%{$search}%")
                    ->orWhere('method', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhereHas('registration.member.user', function ($memberQuery) use ($search) {
                        $memberQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('registration.gymPackage', function ($packageQuery) use ($search) {
                        $packageQuery->where('name', 'like', "%{$search}%");
                    });

                if ($invoiceNumber !== null) {
                    $query->orWhere('id', $invoiceNumber);
                }
            });
        }

        return view('admin.payments.index', [
            'payments' => $payments->get(),
            'registrations' => Registration::with('member.user', 'gymPackage')
                ->whereDoesntHave('payment')
                ->get(),
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.payments.create', [
            'registrations' => Registration::with('member.user', 'gymPackage')
                ->whereDoesntHave('payment')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_id' => ['required', 'exists:registrations,id', 'unique:payments,registration_id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,paid,cancel'],
            'payment_date' => ['nullable', 'date'],
        ]);

        $validated['method'] = 'invoice';

        Payment::create($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Da tao thanh toan.');
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,paid,cancel'],
            'payment_date' => ['nullable', 'date'],
        ]);

        $validated['method'] = 'invoice';

        $payment->update($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Da cap nhat thanh toan.');
    }

    public function edit(Payment $payment)
    {
        $payment->load('registration.member.user', 'registration.gymPackage');

        return view('admin.payments.edit', [
            'payment' => $payment,
            'registrations' => Registration::with('member.user', 'gymPackage')
                ->get(),
        ]);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return back()->with('success', 'Da xoa thanh toan.');
    }
}
