<?php

declare(strict_types=1);

namespace App\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

final class MessengerIdStamp implements StampInterface
{
    public string $messageId;

    public function __construct()
    {
        $this->messageId = uuid_create();
    }
}
