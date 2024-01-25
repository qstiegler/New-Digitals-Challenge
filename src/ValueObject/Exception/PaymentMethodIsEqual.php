<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class PaymentMethodIsEqual extends InternalException
{
    public function __construct()
    {
        parent::__construct('The payment method is equal to the existing one');
    }
}
