<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class MustBeTypeUser extends InternalException
{
    public function __construct()
    {
        parent::__construct('Must be type user');
    }
}
