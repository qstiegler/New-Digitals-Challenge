<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class TimeFromMustBeBeforeTimeTo extends UserFacingException
{
    public function __construct()
    {
        parent::__construct('Die Zeit Von muss vor der Zeit Bis sein.');
    }
}
