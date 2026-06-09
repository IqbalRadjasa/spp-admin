<?php

namespace App\Services\Notifications;

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

        $message =
        "🎓 *SPP Payment Confirmation*

        Your tuition payment has been successfully received.

        ━━━━━━━━━━━━━━

        👤 *Student Name*
        {$student->name}

        🧾 *Payment Code*
        {$payment->payment_code}

        💰 *Amount Paid*
        " . rupiah($payment->amount_paid) . "

        📅 *Paid At*
        " . $payment->paid_at->format('d M Y H:i') . "

        ━━━━━━━━━━━━━━

        Thank you for your payment 🙏";


        $this->logChannel->send($student->parent_phone, $message);
        $this->whatsAppChannel->send($student->parent_phone, $message);
    }
}
