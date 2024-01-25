<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class DateIsNotOnWeekday extends InternalException
{
    public function __construct()
    {
        parent::__construct('The date is not on weekday.');
    }
}
