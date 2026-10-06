<?php

namespace App\Notifications;

use App\Models\Student;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentLinkedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Student $student) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pemberitahuan: Penautan Data Siswa Baru')
            ->greeting("Yth. Bapak/Ibu {$notifiable->name},")
            ->line("Kami menginformasikan bahwa data siswa berikut telah berhasil ditautkan ke akun Anda:")
            ->line("**Nama Lengkap:** {$this->student->fullname}")
            ->line("**NIS:** {$this->student->nis}")
            ->line("**Kelas:** " . ($this->student->classroom?->display_name ?? '-'))
            ->action('Lihat Detail Siswa', route('login'))
            ->line('Jika Anda merasa tidak melakukan penautan ini atau terdapat kekeliruan data, silakan hubungi pihak administrasi sekolah.')
            ->salutation("Salam hangat,\nTim Administrasi Sekolah");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
