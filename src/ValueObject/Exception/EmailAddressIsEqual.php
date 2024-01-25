<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\InternalException;
use App\ValueObject\EmailAddress;

/** @psalm-immutable */
final class EmailAddressIsEqual extends InternalException
{
    public function __construct(EmailAddress $emailAddress)
    {
        parent::__construct(sprintf('Email address is equal to %s', (string) $emailAddress));
    }
}
