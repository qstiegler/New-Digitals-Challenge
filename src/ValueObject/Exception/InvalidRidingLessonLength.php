<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class InvalidRidingLessonLength extends UserFacingException
{
    public function __construct(int $length)
    {
        parent::__construct(sprintf('Die Länge %d ist keine valide Option', $length));
    }
}
