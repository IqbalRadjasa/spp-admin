<?php

namespace App\Services\Notifications\Channels;

use Illuminate\Support\Facades\Http;

use App\Services\Notifications\Channels\Contracts\NotificationChannelInterface;

class WhatsAppChannel implements NotificationChannelInterface
{
    public function send(
        string $target,
        string $message
    ) {
        Http::withHeaders([
            'Authorization' => config('services.fonnte.token')
        ])->post(
            'https://api.fonnte.com/send',
            [

                'target' => $target,

                'message' => $message

            ]
        );
    }
}
