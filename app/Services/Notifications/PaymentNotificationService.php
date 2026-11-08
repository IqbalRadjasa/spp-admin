<?php

namespace App\Services\Notifications;

use App\Models\Bill;
use App\Models\Payment;

use App\Services\Notifications\Channels\LogChannel;
use App\Services\Notifications\Channels\WhatsAppChannel;

class PaymentNotificationService
{

    public function __construct(protected LogChannel $logChannel, protected WhatsAppChannel $whatsAppChannel) {}

    public function sendPaymentSuccess(Payment $payment)
    {
        $student =
            $payment->bill->student;

        $parent = $student?->parents()->first();
        if (!$parent || !$parent->phone) {
            return;
        }

        $message =
            "🎓 *SPP Payment Confirmation*

        Your tuition payment has been successfully received.

        ━━━━━━━━━━━━━━

        👤 *Student Name*
        {$student->fullname}

        🧾 *Payment Code*
        {$payment->payment_code}

        💰 *Amount Paid*
        " . rupiah($payment->amount_paid) . "

        📅 *Paid At*
        " . $payment->paid_at->format('d M Y H:i') . "

        ━━━━━━━━━━━━━━

        Thank you for your payment 🙏";

        $this->logChannel->send($parent->phone, $message);
        $this->whatsAppChannel->send($parent->phone, $message);
    }

    public function sendOverdueReminder(Bill $bill)
    {
        $student = $bill->student;

        $parent = $student?->parents()->first();
        if (!$parent || !$parent->phone) {
            return;
        }

        $message =
            "⚠️ *SPP Payment Reminder*

            Your tuition payment has not been completed.

            ------------------------

            👤 *Student Name*
            {$student->name}

            📅 *Billing Period*
            {$bill->billing_period}

            💰 *Amount*
            " . rupiah($bill->amount) . "

            ------------------------

            Please complete the payment as soon as possible 🙏";

        $this->logChannel->send($parent->phone, $message);
        $this->whatsAppChannel->send($parent->phone, $message);
    }
}
