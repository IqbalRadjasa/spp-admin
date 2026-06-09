<?php

namespace App\Services\Notifications\Channels;


use App\Services\Notifications\Channels\Contracts\NotificationChannelInterface;

class LogChannel implements  NotificationChannelInterface
{
    public function send(
        string $target,
        string $message
    ) {
        logger(
            "[LOG][$target] " .
                $message
        );
    }
}
