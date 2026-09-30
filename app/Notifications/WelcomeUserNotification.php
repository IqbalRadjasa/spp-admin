<?php

namespace App\Notifications;

use App\Jobs\SendWhatsappNotificationJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $plainPassword
    ) {}

    /**
     * Notification channels
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Gmail SMTP Handler
     */
    public function toMail(object $notifiable): MailMessage
    {
        $this->dispatchWhatsapp($notifiable);

        return (new MailMessage)
            ->subject('Selamat Datang - Akun Anda Telah Dibuat')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Akun Anda telah berhasil dibuat di sistem.')
            ->line('Berikut adalah kredensial login Anda:')
            ->line('**Email:** ' . $notifiable->email)
            ->line('**Password Sementara:** ' . $this->plainPassword)
            ->action('Login ke Sistem', route('login'))
            ->line('Demi keamanan, harap segera ubah password Anda setelah berhasil login pertamakali.');
    }

    /**
     * Dispatch WhatsApp Job to Service
     */
    protected function dispatchWhatsapp(object $notifiable): void
    {
        $targetPhone = $notifiable->parentDetail?->phone ?? $notifiable->phone ?? null;

        if (! $targetPhone) {
            return;
        }

        $message = "Halo, {$notifiable->name}!\n\n"
            . "Akun Anda telah dibuat di Sistem Sekolah.\n"
            . "Detail Login:\n"
            . "Email: {$notifiable->email}\n"
            . "Password Sementara: {$this->plainPassword}\n\n"
            . "Silakan login di: " . route('login') . "\n"
            . "Harap segera ganti password Anda setelah login.";

        // Dispatch Job to Queue
        SendWhatsappNotificationJob::dispatch($targetPhone, $message);
    }
}
