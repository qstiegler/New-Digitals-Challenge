<?php

declare(strict_types=1);

namespace App\Time;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;
}
