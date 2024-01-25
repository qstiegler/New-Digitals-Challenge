<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class InvoiceAddressIsEqual extends InternalException
{
    public function __construct()
    {
        parent::__construct('The invoice address is equal to the existing one');
    }
}
