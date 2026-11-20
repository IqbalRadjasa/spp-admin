<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\SchoolProfile;

use App\Services\ActivityLogService;

use App\Jobs\SendPaymentNotificationJob;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $isAlreadyPaid = $bill->status === 'paid' || $bill->payment()->where('status', 'paid')->exists();

        if ($isAlreadyPaid) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tagihan ini sudah lunas.'
                ], 422);
            }

            return back()->with('error', 'Tagihan ini sudah lunas.');
        }

        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'paid_at' => 'required',
            'amount_paid' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'cash_payment_proof' => 'required_if:payment_method_id,1|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ((int) $validated['amount_paid'] !== (int) $bill->amount) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nominal pembayaran tidak sesuai dengan tagihan.'
                ], 422);
            }

            return back()->withInput()->withErrors([
                'amount_paid' => 'The payment amount is incorrect'
            ]);
        }

        $paymentCode = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // 1. Cash Payment Flow
        if ((int) $validated['payment_method_id'] === 1) {
            $proofPath = null;

            if ($request->hasFile('cash_payment_proof')) {
                $proofPath = $request->file('cash_payment_proof')->store('payment-proofs', 'public');
            }

            try {
                $payment = null;

                DB::transaction(function () use (
                    &$payment,
                    $paymentCode,
                    $bill,
                    $validated,
                    $proofPath,
                    $activityLog
                ) {
                    $lockedBill = Bill::where('id', $bill->id)->lockForUpdate()->first();

                    if (!$lockedBill || $lockedBill->status === 'paid') {
                        throw new \RuntimeException('Tagihan ini sudah lunas.');
                    }

                    // Clear any leftover pending payments for this bill so they don't linger
                    Payment::where('bill_id', $lockedBill->id)
                        ->where('status', 'pending')
                        ->lockForUpdate()
                        ->update(['status' => 'failed']);

                    $payment = Payment::create([
                        'payment_code' => $paymentCode,
                        'bill_id' => $lockedBill->id,
                        'payment_method_id' => $validated['payment_method_id'],
                        'status' => 'paid',
                        'paid_at' => $validated['paid_at'],
                        'amount_paid' => (int) $validated['amount_paid'],
                        'notes' => $validated['notes'] ?? null,
                        'cash_payment_proof' => $proofPath,
                    ]);

                    $lockedBill->update([
                        'status' => 'paid',
                        'reminder_attempts' => 0,
                        'last_reminded_at' => null,
                        'escalation_status' => 'resolved',
                    ]);

                    $activityLog->log(
                        'pembayaran',
                        'bill',
                        $lockedBill->id,
                        'Pembayaran Tagihan Tunai: ' . $payment->payment_code
                    );
                });

                SendPaymentNotificationJob::dispatch($payment);

                return redirect()
                    ->route('bills.index')
                    ->with([
                        'success' => 'Pembayaran tunai telah berhasil dicatat!',
                    ]);
            } catch (\Throwable $e) {
                if ($proofPath && Storage::disk('public')->exists($proofPath)) {
                    Storage::disk('public')->delete($proofPath);
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage() ?: 'Gagal memproses pembayaran tunai.'
                    ], 422);
                }

                return back()->withInput()->with('error', $e->getMessage() ?: 'Gagal memproses pembayaran tunai.');
            }
        }

        // 2. Midtrans Snap Payment Flow
        if ((int) $validated['payment_method_id'] === 2) {

            try {
                $payment = null;

                DB::transaction(function () use (&$payment, $paymentCode, $bill, $validated) {
                    
                    $lockedBill = Bill::where('id', $bill->id)->lockForUpdate()->first();

                    if (!$lockedBill || $lockedBill->status === 'paid') {
                        throw new \RuntimeException('Tagihan ini sudah lunas.');
                    }
                    $payment = Payment::where('bill_id', $lockedBill->id)
                        ->where('status', 'pending')
                        ->lockForUpdate()
                        ->first();

                    if ($payment) {
                        $payment->update([
                            'payment_code' => $paymentCode,
                            'payment_method_id' => $validated['payment_method_id'],
                            'paid_at' => $validated['paid_at'],
                            'amount_paid' => (int) $validated['amount_paid'],
                            'notes' => $validated['notes'] ?? null,
                        ]);
                    } else {
                        $payment = Payment::create([
                            'payment_code' => $paymentCode,
                            'bill_id' => $lockedBill->id,
                            'payment_method_id' => $validated['payment_method_id'],
                            'status' => 'pending',
                            'paid_at' => $validated['paid_at'],
                            'amount_paid' => (int) $validated['amount_paid'],
                            'notes' => $validated['notes'] ?? null,
                        ]);
                    }
                });

                $student = $bill->student;
                $parent = $student?->parents()->first();

                $params = [
                    'transaction_details' => [
                        'order_id' => $payment->payment_code,
                        'gross_amount' => (int) $payment->amount_paid,
                    ],
                    'customer_details' => [
                        'first_name' => $student?->fullname ?? $student?->nickname ?? 'Customer',
                        'email' => $parent?->email ?? $student?->email ?? 'customer@example.com',
                        'phone' => $parent?->phone ?? $parent?->pivot?->phone ?? '08123456789',
                    ],
                    'enabled_payments' => [
                        'bca_va',
                        'bni_va',
                        'bri_va',
                        'gopay',
                        'shopeepay',
                        'qris',
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'payment_code' => $payment->payment_code,
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], $e instanceof \RuntimeException ? 422 : 500);
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
        $paymentCode       = $notif->order_id;

        // Map Midtrans status to database status enum
        $newPaymentStatus = 'pending';

        if ($transactionStatus == 'capture') {
            $newPaymentStatus = ($fraudStatus == 'challenge') ? 'failed' : 'paid';
        } elseif ($transactionStatus == 'settlement') {
            $newPaymentStatus = 'paid';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
            $newPaymentStatus = 'failed';
        }

        $result = DB::transaction(function () use ($paymentCode, $newPaymentStatus) {
            $payment = Payment::where('payment_code', $paymentCode)->lockForUpdate()->first();

            if (!$payment) {
                return ['status' => 'not_found'];
            }

            // Track the previous status before updating
            $oldStatus = $payment->status;

            // If the status is already what it's supposed to be, do nothing
            if ($oldStatus === $newPaymentStatus) {
                return ['status' => 'already_processed', 'should_notify' => false];
            }

            // 1. Update Payment Status
            $payment->update([
                'status' => $newPaymentStatus,
                'paid_at' => ($newPaymentStatus === 'paid' && $oldStatus !== 'paid') ? now() : $payment->paid_at,
            ]);

            // 2. If newly paid successfully, update the Bill status to 'paid'
            if ($newPaymentStatus === 'paid') {
                $payment->bill->update([
                    'status' => 'paid',
                    'reminder_attempts' => 0,
                    'last_reminded_at' => null,
                    'escalation_status' => 'resolved'
                ]);
            }

            // Determine if we should dispatch notification:
            // Only true if the status changed *to* 'paid' right now.
            $shouldNotify = ($oldStatus !== 'paid' && $newPaymentStatus === 'paid');

            return [
                'status' => 'processed',
                'payment' => $payment,
                'should_notify' => $shouldNotify
            ];
        });

        if ($result['status'] === 'not_found') {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        if ($result['status'] === 'already_processed' || !$result['should_notify']) {
            return response()->json(['message' => 'Pembayaran telah diproses atau status tidak memerlukan notifikasi']);
        }

        // Dispatch notification ONLY if it transitioned successfully to paid
        SendPaymentNotificationJob::dispatch($result['payment']);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification handled successfully'
        ], 200);
    }

    public function downloadReceipt(Payment $payment)
    {
        $school = SchoolProfile::first();
        $payment->load(['bill.student.parents', 'paymentMethod']);
        $formattedDate = Carbon::parse($payment->paid_at)->isoFormat('D MMMM YYYY [, pukul] HH:mm');

        $pdf = Pdf::loadView('payments.receipt-pdf', compact('payment', 'school', 'formattedDate'));

        // Set paper size to A4
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Kuitansi-SPP-' . $payment->payment_code . '-' . $payment->bill->student->fullname ?? $payment->bill->student->nickname ?? 'Siswa' . '.pdf';

        // Stream or download directly with the custom filename pre-filled
        return $pdf->download($filename);
    }
}
