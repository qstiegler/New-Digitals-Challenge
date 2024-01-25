<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class MissingRequiredPermission extends InternalException
{
    public function __construct(string $permission, string $requiredPermission)
    {
        parent::__construct(sprintf(
            'The permission %s was passed but permission %s is missing',
            $permission,
            $requiredPermission,
        ));
    }
}
