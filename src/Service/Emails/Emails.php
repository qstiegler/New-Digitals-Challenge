<?php

declare(strict_types=1);

namespace App\Service\Emails;

use App\ValueObject\EmailAddress;

interface Emails
{
    public function sendEmailFromDefault(
        EmailAddress $toEmail,
        string $toName,
        string $subject,
        string $body,
    ): void;
}
