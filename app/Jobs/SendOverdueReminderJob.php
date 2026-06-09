<?php

namespace App\Jobs;

use App\Models\Bill;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Notifications\PaymentNotificationService;

class SendOverdueReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Bill $bill) {}

    public function handle(PaymentNotificationService $notificationService): void
    {
        $notificationService->sendOverdueReminder($this->bill);
    }
}
