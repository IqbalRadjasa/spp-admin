<?php

namespace App\Jobs;

use App\Models\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Notifications\PaymentNotificationService;

class SendPaymentNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Payment $payment) {}

    public function handle(PaymentNotificationService $notificationService): void
    {
        $notificationService->sendPaymentSuccess($this->payment);
    }
}
