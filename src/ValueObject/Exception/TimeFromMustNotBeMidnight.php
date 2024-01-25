<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class TimeFromMustNotBeMidnight extends UserFacingException
{
    public function __construct()
    {
        parent::__construct('Die Zeit von kann nicht um Mitternacht sein.');
    }
}
