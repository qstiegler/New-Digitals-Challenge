<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class PhoneNumberInvalid extends UserFacingException
{
    public function __construct(string $phoneNumber)
    {
        parent::__construct(sprintf('Die Telefonnummer %s ist leider nicht valide', $phoneNumber));
    }
}
