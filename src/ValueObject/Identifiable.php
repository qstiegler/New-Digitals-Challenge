<?php

declare(strict_types=1);

namespace App\ValueObject;

interface Identifiable
{
    /** @psalm-pure */
    public function getIdentifier(): \Stringable;
}
