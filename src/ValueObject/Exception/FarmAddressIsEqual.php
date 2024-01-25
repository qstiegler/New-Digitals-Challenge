<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class FarmAddressIsEqual extends UserFacingException
{
    public function __construct()
    {
        parent::__construct('Die Adresse ist identisch mit der aktuellen Adresse.');
    }
}
