<?php

declare(strict_types=1);

namespace App\ValueObject;

interface ArrayNormalizable
{
    public static function denormalize(array $array): self;

    public function normalize(): array;
}
