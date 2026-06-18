<?php

namespace App\Http\Controllers;


use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentMethod;

use App\Services\ActivityLogService;

use App\Jobs\SendPaymentNotificationJob;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request, Bill $bill, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'paid_at' => 'required',
            'amount_paid' => 'required|integer|min:1',
            'notes' => 'nullable'
        ]);

        if ($validated['amount_paid'] != $bill->amount) {

            return back()->withErrors([
                'amount_paid' => 'The payment amount is incorrect'
            ]);
        }

        $latestPayment = Payment::latest()->first();

        $nextNumber = $latestPayment
            ? $latestPayment->id + 1
            : 1;

        $paymentCode =
            'PAY-' .
            now()->format('Ymd') .
            '-' .
            str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $payment = null;

        DB::transaction(
            function () use (&$payment, $paymentCode, $bill, $validated, $activityLog) {
                $payment = Payment::create([
                    'payment_code' => $paymentCode,
                    'bill_id' => $bill->id,
                    'payment_method_id' => $validated['payment_method_id'],
                    'paid_at' => $validated['paid_at'],
                    'amount_paid' => $validated['amount_paid'],
                    'notes' => $validated['notes']
                ]);

                $bill->update([
                    'status' => 'paid',
                    'reminder_attempts' => 0,
                    'last_reminded_at' => null,
                    'escalation_status' => 'resolved'
                ]);

                $activityLog->log(
                    'payment',
                    'bill',
                    $bill->id,
                    'Bill Payment ' .
                        $payment['payment_code']
                );
            }
        );

        // Queue Notification
        SendPaymentNotificationJob::dispatch($payment);

        return redirect()
            ->route('payments.receipt', $payment)
            ->with('success', 'Payment successful!');
    }

    public function receipt(Payment $payment)
    {
        $payment->load([
            'bill.student',
            'paymentMethod'
        ]);

        return view(
            'payments.receipt',
            compact('payment')
        );
    }
}
