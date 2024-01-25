<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class InvalidPostal extends UserFacingException
{
    public function __construct()
    {
        parent::__construct('Die PLZ ist ungültig.');
    }
}
