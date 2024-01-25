<?php

declare(strict_types=1);

namespace App\EventSubscriber\DTO;

final class ErrorResponse
{
    public function __construct(
        public string $message,
        public int $statusCode,
    ) {
    }
}
