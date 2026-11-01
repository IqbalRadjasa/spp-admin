<?php


namespace App\Http\Controllers\Parent;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentMethod;

use Midtrans\Snap;
use Midtrans\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaymentController extends ParentController
{
    public function generateSnapToken(Request $request, Bill $bill)
    {
        $user = auth()->user();

        // 1. SECURITY: Ensure the parent actually owns the student attached to this bill
        $ownsBill = $user->children()->where('students.id', $bill->student_id)->exists();

        if (!$ownsBill) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access.'], 403);
        }

        // 2. Prevent paying already paid bills
        if ($bill->status === 'paid') {
            return response()->json(['status' => 'error', 'message' => 'Tagihan ini sudah lunas.'], 400);
        }

        // 3. Generate a unique payment code (e.g., PAY-20261101-XXXX)
        $paymentCode = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // 4. Create initial pending payment record
        // (Assuming you have a default online payment method ID or you grab the first active one)
        $paymentMethodId = PaymentMethod::select('id')->where('id', 2)->first()->id;

        $payment = DB::transaction(function () use ($paymentCode, $bill, $paymentMethodId) {
            return Payment::create([
                'payment_code' => $paymentCode,
                'bill_id' => $bill->id,
                'payment_method_id' => $paymentMethodId,
                'status' => 'pending',
                'paid_at' => now(),
                'amount_paid' => $bill->amount,
                'notes' => 'Online payment via Midtrans Snap (Parent Portal)'
            ]);
        });

        // 5. Prepare Midtrans payload using your exact working structure
        $studentParent = $user->studentParent;
        $student = $bill->student;

        $params = [
            'transaction_details' => [
                'order_id' => $payment->payment_code,
                'gross_amount' => (int) $payment->amount_paid,
            ],
            'customer_details' => [
                'first_name' => $student->fullname ?? $student->name,
                'email' => $studentParent->email ?? $user->email ?? 'customer@example.com',
                'phone' => $studentParent->phone ?? '08123456789'
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

        try {
            $snapToken = Snap::getSnapToken($params);

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
