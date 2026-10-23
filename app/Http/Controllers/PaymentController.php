<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentMethod;

use App\Services\ActivityLogService;

use App\Jobs\SendPaymentNotificationJob;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized  = config('midtrans.isSanitized');
        Config::$is3ds        = config('midtrans.is3ds');
    }

    public function create(Bill $bill)
    {
        if ($bill->status === 'paid') {
            abort(404);
        }

        $bill->load([
            'student' => function ($q) {
                $q->select(['id', 'fullname', 'nis']);
            }
        ]);

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
            'notes' => 'nullable',
            'cash_payment_proof' => 'required_if:payment_method_id,1|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validated['amount_paid'] != $bill->amount) {
            return back()->withErrors([
                'amount_paid' => 'The payment amount is incorrect'
            ]);
        }

        $latestPayment = Payment::latest()->first();
        $nextNumber = $latestPayment ? $latestPayment->id + 1 : 1;
        $paymentCode = 'PAY-' . now()->format('Ymd') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        if ($validated['payment_method_id'] == 1) {
            $proofPath = null;
            if ($request->hasFile('cash_payment_proof')) {
                $proofPath = $request->file('cash_payment_proof')->store('payment-proofs', 'public');
            }

            $payment = null;

            DB::transaction(
                function () use (&$payment, $paymentCode, $bill, $validated, $proofPath, $activityLog) {
                    $payment = Payment::create([
                        'payment_code' => $paymentCode,
                        'bill_id' => $bill->id,
                        'payment_method_id' => $validated['payment_method_id'],
                        'status' => 'paid', // Cash is instantly paid
                        'paid_at' => $validated['paid_at'],
                        'amount_paid' => $validated['amount_paid'],
                        'notes' => $validated['notes'],
                        'cash_payment_proof' => $proofPath,
                    ]);

                    $bill->update([
                        'status' => 'paid',
                        'reminder_attempts' => 0,
                        'last_reminded_at' => null,
                        'escalation_status' => 'resolved'
                    ]);

                    $activityLog->log(
                        'pembayaran',
                        'bill',
                        $bill->id,
                        'Pembayaran Tagihan Tunai: ' . $payment['payment_code']
                    );
                }
            );

            // Queue Notification
            SendPaymentNotificationJob::dispatch($payment);

            return redirect()
                ->route('payments.receipt', $payment)
                ->with('success', 'Cash payment recorded successfully!');
        }

        if ($validated['payment_method_id'] == 2) {
            DB::transaction(
                function () use (&$payment, $paymentCode, $bill, $validated) {
                    // Create initial record as 'pending'
                    $payment = Payment::create([
                        'payment_code' => $paymentCode,
                        'bill_id' => $bill->id,
                        'payment_method_id' => $validated['payment_method_id'],
                        'status' => 'pending',
                        'paid_at' => $validated['paid_at'],
                        'amount_paid' => $validated['amount_paid'],
                        'notes' => $validated['notes']
                    ]);
                }
            );

            $parent = $bill->student->parents()->first();
            $params = [
                'transaction_details' => [
                    'order_id'     => $payment->payment_code, // Use our unique payment code
                    'gross_amount' => (int) $payment->amount_paid,
                ],
                'customer_details' => [
                    'first_name' => $bill->student->name ?? 'Customer',
                    'email'      => $parent->email ?? $bill->student->email ?? 'customer@example.com',
                    'phone'      => $parent->phone ?? $parent->pivot->phone ?? '08123456789'
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);

                // Return JSON for the frontend to launch the Snap popup
                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'payment_code' => $payment->payment_code
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function notification(Request $request, Notification $midtrans_notification)
    {
        try {
            $notif = $midtrans_notification;
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        $transactionStatus = $notif->transaction_status;
        $fraudStatus       = $notif->fraud_status;
        $paymentCode       = $notif->order_id; // This is your 'PAY-20261022-0001'

        // Find the payment record
        $payment = Payment::where('payment_code', $paymentCode)->first();

        if (!$payment) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Prevent duplicate updates if already paid/failed
        if ($payment->status === 'paid') {
            return response()->json(['message' => 'Pembayaran telah diproses']);
        }

        // Map Midtrans status to your database status enum ('pending', 'paid', 'failed')
        $newPaymentStatus = 'pending';

        if ($transactionStatus == 'capture') {
            $newPaymentStatus = ($fraudStatus == 'challenge') ? 'failed' : 'paid';
        } elseif ($transactionStatus == 'settlement') {
            $newPaymentStatus = 'paid';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
            $newPaymentStatus = 'failed';
        }

        DB::transaction(function () use ($payment, $newPaymentStatus, $notif) {
            // 1. Update Payment Status
            $payment->update([
                'status' => $newPaymentStatus,
                'paid_at' => ($newPaymentStatus === 'paid') ? now() : $payment->paid_at,
                // Optionally save Midtrans details or payment type if you have columns for them
            ]);

            // 2. If paid successfully, update the Bill status to 'paid'
            if ($newPaymentStatus === 'paid') {
                $payment->bill->update([
                    'status' => 'paid',
                    'reminder_attempts' => 0,
                    'last_reminded_at' => null,
                    'escalation_status' => 'resolved'
                ]);
            }
        });

        return response()->json(['message' => 'Pemberitahuan telah diproses dengan sukses']);
    }

    // public function store(Request $request, Bill $bill, ActivityLogService $activityLog)
    // {
    //     $validated = $request->validate([
    //         'payment_method_id' => 'required|exists:payment_methods,id',
    //         'paid_at' => 'required',
    //         'amount_paid' => 'required|integer|min:1',
    //         'notes' => 'nullable'
    //     ]);

    //     if ($validated['amount_paid'] != $bill->amount) {

    //         return back()->withErrors([
    //             'amount_paid' => 'The payment amount is incorrect'
    //         ]);
    //     }

    //     $latestPayment = Payment::latest()->first();

    //     $nextNumber = $latestPayment
    //         ? $latestPayment->id + 1
    //         : 1;

    //     $paymentCode =
    //         'PAY-' .
    //         now()->format('Ymd') .
    //         '-' .
    //         str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    //     $payment = null;

    //     DB::transaction(
    //         function () use (&$payment, $paymentCode, $bill, $validated, $activityLog) {
    //             $payment = Payment::create([
    //                 'payment_code' => $paymentCode,
    //                 'bill_id' => $bill->id,
    //                 'payment_method_id' => $validated['payment_method_id'],
    //                 'paid_at' => $validated['paid_at'],
    //                 'amount_paid' => $validated['amount_paid'],
    //                 'notes' => $validated['notes']
    //             ]);

    //             $bill->update([
    //                 'status' => 'paid',
    //                 'reminder_attempts' => 0,
    //                 'last_reminded_at' => null,
    //                 'escalation_status' => 'resolved'
    //             ]);

    //             $activityLog->log(
    //                 'payment',
    //                 'bill',
    //                 $bill->id,
    //                 'Bill Payment ' .
    //                     $payment['payment_code']
    //             );
    //         }
    //     );

    //     // Queue Notification
    //     SendPaymentNotificationJob::dispatch($payment);

    //     return redirect()
    //         ->route('payments.receipt', $payment)
    //         ->with('success', 'Payment successful!');
    // }

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
