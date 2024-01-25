<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class ItemDoesNotExist extends InternalException
{
    public function __construct(\Stringable $identifier)
    {
        parent::__construct(sprintf('Item with id %s does not exist', (string) $identifier));
    }
}
