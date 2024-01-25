<?php

declare(strict_types=1);

namespace App\Time;

interface Clock
{
    public function now(): \DateTimeImmutable;
}
