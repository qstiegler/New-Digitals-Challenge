<?php

declare(strict_types=1);

namespace App\Test\Service\Emails;

use App\Service\Emails\Emails;
use App\ValueObject\EmailAddress;

final class EmailsFake implements Emails
{
    /** @var array<int, DTO\EmailMessage> */
    public array $emailMessages = [];

    public function sendEmailFromDefault(
        EmailAddress $toEmail,
        string $toName,
        string $subject,
        string $body,
    ): void {
        $this->emailMessages[] = new DTO\EmailMessage(
            new EmailAddress('default@example.com'),
            'Default',
            $toEmail,
            $toName,
            $subject,
            $body,
        );
    }
}
