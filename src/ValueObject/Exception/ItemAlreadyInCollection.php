<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class ItemAlreadyInCollection extends InternalException
{
    public function __construct(\Stringable $identifier)
    {
        parent::__construct(sprintf('Item with identifier %s is already part of the collection', (string) $identifier));
    }
}
