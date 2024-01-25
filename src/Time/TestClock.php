<?php

declare(strict_types=1);

namespace App\Time;

final class TestClock implements ClockInterface
{
    private \DateTimeImmutable $now;

    public function __construct(
        \DateTimeImmutable $now = null,
    ) {
        $this->now = $now ?? self::getDateTimeImmutableWithoutMilliseconds(new \DateTimeImmutable('now'));
    }

    public function fixate(\DateTimeImmutable $now): void
    {
        $this->now = $now;
    }

    public function now(): \DateTimeImmutable
    {
        return $this->now;
    }

    public function nowWithoutMilliseconds(): \DateTimeImmutable
    {
        return self::getDateTimeImmutableWithoutMilliseconds($this->now);
    }

    public static function getDateTimeImmutableWithoutMilliseconds(
        \DateTimeImmutable $dateTimeImmutable,
    ): \DateTimeImmutable {
        return new \DateTimeImmutable($dateTimeImmutable->format('Y-m-d H:i:s'));
    }
}
