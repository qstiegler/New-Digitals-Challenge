<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class UserRoleIsEqual extends InternalException
{
    public function __construct()
    {
        parent::__construct('The user role is equal to the existing one');
    }
}
