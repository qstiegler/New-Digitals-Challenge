<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class MonthIsNotBeforeCurrentMonth extends UserFacingException
{
    public function __construct()
    {
        parent::__construct('Der ausgewählte Monat muss in der Vergangenheit liegen.');
    }
}
