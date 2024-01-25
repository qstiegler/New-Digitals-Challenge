<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class BankInformationIsEqual extends InternalException
{
    public function __construct()
    {
        parent::__construct('The bank information is equal to the existing one');
    }
}
