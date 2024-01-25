<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class MomentFromMustBeBeforeMomentTo extends UserFacingException
{
    public function __construct()
    {
        parent::__construct('Der Startzeitpunkt muss vor der Endzeitpunkt sein.');
    }
}
