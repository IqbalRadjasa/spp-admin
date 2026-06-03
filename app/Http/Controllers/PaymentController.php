<?php

namespace App\Http\Controllers;


use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentMethod;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Bill $bill)
    {
        if ($bill->status === 'paid') {
            abort(404);
        }

        $paymentMethods = PaymentMethod::all();

        return view('payments.create', compact(
            'bill',
            'paymentMethods'
        ));
    }

    public function store(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'paid_at' => 'required',
            'amount_paid' => 'required|integer|min:1',
            'notes' => 'nullable'
        ]);

        if ($validated['amount_paid'] < $bill->amount || $validated['amount_paid'] > $bill->amount) {
            return back()->withErrors([
                'amount_paid' => 'The payment amount is incorrect'
            ]);
        }

        Payment::create([
            'bill_id' => $bill->id,
            'payment_method_id' => $validated['payment_method_id'],
            'paid_at' => $validated['paid_at'],
            'amount_paid' => $validated['amount_paid'],
            'notes' => $validated['notes']
        ]);

        $bill->update([
            'status' => 'paid'
        ]);

        return redirect()
            ->route('bills.index')
            ->with('success', 'Payment successfull');
    }
}
