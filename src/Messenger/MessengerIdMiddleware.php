<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Messenger\Stamp\MessengerIdStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class MessengerIdMiddleware implements MiddlewareInterface
{
    public function handle(
        Envelope $envelope,
        StackInterface $stack,
    ): Envelope {
        if (null === $envelope->last(MessengerIdStamp::class)) {
            $envelope = $envelope->with(new MessengerIdStamp());
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
