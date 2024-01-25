<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class TermsOfPaymentAreEqual extends InternalException
{
    public function __construct()
    {
        parent::__construct('The terms of payment are equal to the existing one');
    }
}
