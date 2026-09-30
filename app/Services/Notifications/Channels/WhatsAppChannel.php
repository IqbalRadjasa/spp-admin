<?php

namespace App\Services\Notifications\Channels;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Services\Notifications\Channels\Contracts\NotificationChannelInterface;

class WhatsAppChannel implements NotificationChannelInterface
{
    public function send(
        string $target,
        string $message
    ) {
        $token = config('services.fonnte.token');

        if (!$token) {
            Log::error('Fonnte API Token is not set in config/services.php.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($this->apiUrl, [
                'target'  => $target,
                'message' => $message,
            ]);

            if ($response->failed()) {
                Log::error('Fonnte API Request Failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Fonnte Exception: ' . $e->getMessage());
            return false;
        }
    }
}
