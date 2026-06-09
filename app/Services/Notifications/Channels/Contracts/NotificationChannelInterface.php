<?php

namespace App\Services\Notifications\Channels\Contracts;

interface NotificationChannelInterface
{
    public function send(
        string $target,
        string $message
    );
}
