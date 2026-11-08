<?php

namespace App\Jobs;

use App\Models\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Notifications\UpdateBillNotificationService;

class SendUpdateBillNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected $parent, protected $bill, protected $oldNominal, protected $newNominal) {}

    public function handle(UpdateBillNotificationService $notificationService): void
    {
        $notificationService->sendNotification($this->parent, $this->bill, $this->oldNominal, $this->newNominal);
    }
}
