<?php

declare(strict_types=1);

namespace App\Test\Service\Emails\DTO;

use App\ValueObject\EmailAddress;

/** @psalm-immutable */
final class EmailMessage
{
    public function __construct(
        public EmailAddress $fromEmail,
        public ?string $fromName,
        public EmailAddress $toEmail,
        public ?string $toName,
        public string $subject,
        public string $body,
    ) {
    }
}
