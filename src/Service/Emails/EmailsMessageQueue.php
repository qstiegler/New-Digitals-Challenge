<?php

declare(strict_types=1);

namespace App\Service\Emails;

use App\Messenger\SendEmail\SendEmailMessage;
use App\ValueObject\EmailAddress;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class EmailsMessageQueue implements Emails
{
    private EmailAddress $defaultSenderEmailAddress;

    public function __construct(
        private MessageBusInterface $messageBus,
        private string $defaultSenderName,
        private bool $isEmailDispatchEnabled,
        string $defaultSenderEmailAddress,
    ) {
        $this->defaultSenderEmailAddress = new EmailAddress($defaultSenderEmailAddress);
    }

    public function sendEmailFromDefault(
        EmailAddress $toEmail,
        string $toName,
        string $subject,
        string $body,
    ): void {
        if ($this->isEmailDispatchEnabled) {
            $this->messageBus->dispatch(new SendEmailMessage(
                $this->defaultSenderEmailAddress,
                $this->defaultSenderName,
                $toEmail,
                $toName,
                $subject,
                $body,
            ));
        }
    }
}
