<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class PermissionRequiresFeature extends InternalException
{
    public function __construct()
    {
        parent::__construct('A feature is required for a permission.');
    }
}
