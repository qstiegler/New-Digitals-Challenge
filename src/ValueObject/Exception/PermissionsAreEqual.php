<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class PermissionsAreEqual extends UserFacingException
{
    public function __construct()
    {
        parent::__construct('Die Berechtigungen haben sich nicht geändert.');
    }
}
