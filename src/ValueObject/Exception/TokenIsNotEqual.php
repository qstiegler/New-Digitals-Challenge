<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class TokenIsNotEqual extends UserFacingException
{
    public function __construct()
    {
        parent::__construct('Der genutzte Token ist nicht gültig.');
    }
}
