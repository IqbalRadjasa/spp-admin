<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Jobs\SendWhatsappNotificationJob;

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

        if ($notifiable instanceof \App\Models\User && ! $notifiable->relationLoaded('studentParent')) {
            $notifiable->load('studentParent');
        }

        $targetPhone = $notifiable->studentParent?->phone ?? $notifiable->phone ?? null;

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
        if ($notifiable instanceof \App\Models\User && ! $notifiable->relationLoaded('studentParent')) {
            $notifiable->load('studentParent');
        }

        $targetPhone = $notifiable->studentParent?->phone ?? $notifiable->phone ?? null;

        if (! $targetPhone) {
            \Illuminate\Support\Facades\Log::warning("WhatsApp notification skipped: No phone number found for User ID {$notifiable->id}");
            return;
        }

        $message = "Halo, {$notifiable->name}!\n\n"
            . "Akun Anda telah dibuat di Sistem Sekolah.\n"
            . "Detail Login:\n"
            . "Email: {$notifiable->email}\n"
            . "Password Sementara: {$this->plainPassword}\n\n"
            . "Silakan login di: " . route('login') . "\n"
            . "Harap segera ganti password Anda setelah login.";

        SendWhatsappNotificationJob::dispatch($targetPhone, $message);
    }
}
