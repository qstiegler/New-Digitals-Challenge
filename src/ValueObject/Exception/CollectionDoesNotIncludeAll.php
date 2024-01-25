<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class CollectionDoesNotIncludeAll extends InternalException
{
    public function __construct()
    {
        parent::__construct('The collection does not contain all items');
    }
}
