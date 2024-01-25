<?php

declare(strict_types=1);

namespace App\Messenger\SendEmail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final readonly class SendEmailMessageHandler
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    public function __invoke(
        SendEmailMessage $message,
    ): void {
        $fromAddress = new Address((string) $message->fromEmail, $message->fromName ?? '');
        $toAddress = new Address((string) $message->toEmail, $message->toName ?? '');
        $replyToAddress = new Address((string) $message->fromEmail, $message->fromName ?? '');

        $email = new Email();
        $email->from($fromAddress);
        $email->to($toAddress);
        $email->replyTo($replyToAddress);
        $email->subject($message->subject);
        $email->html($message->body);

        $this->mailer->send($email);
    }
}
