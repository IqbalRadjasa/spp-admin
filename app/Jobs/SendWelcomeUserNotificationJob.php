<?php

namespace App\Jobs;

use App\Services\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsappNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $target,
        public string $message
    ) {}

    /**
     * Execute the job.
     */
    public function handle(WhatsAppChannel $whatsAppChannel): void
    {
        $whatsAppChannel->send($this->target, $this->message);
    }
}
