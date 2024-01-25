<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class EmailAddressInvalid extends UserFacingException
{
    public function __construct(string $emailAddress)
    {
        parent::__construct(sprintf('Die E-Mail Adresse %s ist leider nicht valide', $emailAddress));
    }
}
