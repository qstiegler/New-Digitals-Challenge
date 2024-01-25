<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class StartOrEndIsNotAtMidnightInTimeZone extends InternalException
{
    public function __construct()
    {
        parent::__construct('The start or end is not ad midnight in the time zone.');
    }
}
