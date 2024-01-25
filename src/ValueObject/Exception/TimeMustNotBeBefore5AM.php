<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class TimeMustNotBeBefore5AM extends UserFacingException
{
    public function __construct()
    {
        parent::__construct('Die Zeit darf nicht vor 05:00 sein.');
    }
}
