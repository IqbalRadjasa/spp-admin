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
        $student = $payment->bill->student;

        $parent = $student?->parents()->first();
        if (!$parent || !$parent->phone) {
            return;
        }

        $message =
            "🎓 *Konfirmasi Pembayaran SPP*\n\n" .
            "Pembayaran SPP Anda telah berhasil kami terima.\n\n" .
            "━━━━━━━━━━━━━━\n\n" .
            "👤 *Nama Siswa*\n" .
            "{$student->fullname}\n\n" .
            "🧾 *Kode Pembayaran*\n" .
            "{$payment->payment_code}\n\n" .
            "💰 *Jumlah Dibayar*\n" .
            rupiah($payment->amount_paid) . "\n\n" .
            "📅 *Waktu Pembayaran*\n" .
            $payment->paid_at->format('d M Y H:i') . "\n\n" .
            "━━━━━━━━━━━━━━\n\n" .
            "Terima kasih atas pembayaran Anda 🙏";

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

        // Format billing period nicely if it's a date string (e.g., '2026-11')
        $formattedPeriod = \Carbon\Carbon::hasFormat($bill->billing_period, 'Y-m')
            ? \Carbon\Carbon::parse($bill->billing_period)->translatedFormat('F Y')
            : $bill->billing_period;

        $message =
            "⚠️ *Pengingat Pembayaran SPP*\n\n" .
            "Tagihan SPP Anda belum diselesaikan.\n\n" .
            "------------------------\n\n" .
            "👤 *Nama Siswa*\n" .
            "{$student->fullname}\n\n" .
            "📅 *Periode Tagihan*\n" .
            "{$formattedPeriod}\n\n" .
            "💰 *Jumlah Tagihan*\n" .
            rupiah($bill->amount) . "\n\n" .
            "------------------------\n\n" .
            "Mohon segera lakukan pembayaran secepatnya 🙏";

        $this->logChannel->send($parent->phone, $message);
        $this->whatsAppChannel->send($parent->phone, $message);
    }
}
