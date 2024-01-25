<?php

declare(strict_types=1);

namespace App\Messenger\SendEmail;

use App\Messenger\MessageInterface;
use App\ValueObject\EmailAddress;

final readonly class SendEmailMessage implements MessageInterface
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
