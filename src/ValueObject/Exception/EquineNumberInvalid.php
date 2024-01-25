<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class EquineNumberInvalid extends UserFacingException
{
    public function __construct(string $equineNumber)
    {
        parent::__construct(sprintf('Die Lebensnummer %s ist leider nicht valide', $equineNumber));
    }
}
