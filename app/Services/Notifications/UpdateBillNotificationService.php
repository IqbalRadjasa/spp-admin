<?php

namespace App\Services\Notifications;

use App\Models\Bill;
use App\Models\Payment;

use App\Services\Notifications\Channels\LogChannel;
use App\Services\Notifications\Channels\WhatsAppChannel;
use Carbon\Carbon;

class UpdateBillNotificationService
{

    public function __construct(protected LogChannel $logChannel, protected WhatsAppChannel $whatsAppChannel) {}

    public function sendNotification($parent, $bill, $oldNominal, $newNominal)
    {
        $excessAmount = $oldNominal - $newNominal;

        $periodDate = Carbon::parse($bill->billing_period);
        $formattedPeriod = $periodDate->translatedFormat('F Y'); // e.g., "Januari 2023"

        $message =
            "PENGUMUMAN PENTING SEKOLAH\n\n" .
            "Halo Bpk/Ibu *{$parent->name}*,\n\n" .
            "Kami menginformasikan bahwa terdapat penyesuaian/koreksi nominal pada tagihan periode *{$formattedPeriod}*.\n\n" .
            "• Nominal Sebelumnya: Rp " . number_format($oldNominal, 0, ',', '.') . "\n" .
            "• Nominal Terbaru: Rp " . number_format($newNominal, 0, ',', '.') . "\n" .
            "• Selisih Kelebihan Pembayaran: *Rp " . number_format($excessAmount, 0, ',', '.') . "*\n\n" .
            "Karena Bapak/Ibu sebelumnya telah melakukan pembayaran, pihak sekolah akan memproses pengembalian dana (refund) untuk selisih tersebut.\n\n" .
            "Mohon bantuannya untuk membalas pesan ini dengan:\n" .
            "1. Foto/File *Kuitansi Pembayaran* sebagai bukti.\n" .
            "2. Informasi *Nama Bank, Nomor Rekening, dan Atas Nama* untuk tujuan transfer refund.\n\n" .
            "Proses pengembalian dana ini diperkirakan memakan waktu maksimal *24 jam* setelah data rekening diterima. Jika dana belum diterima dalam waktu tersebut, mohon segera hubungi pihak tata usaha sekolah.\n\n" .
            "Mohon maaf atas ketidaknyamanannya dan terima kasih atas kerja sama Anda.";


        $this->logChannel->send($parent->phone, $message);
        $this->whatsAppChannel->send($parent->phone, $message);
    }
}
