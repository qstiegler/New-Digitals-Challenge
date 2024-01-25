<?php

declare(strict_types=1);

namespace App\ValueObject\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class InvalidPassword extends UserFacingException
{
    public function __construct()
    {
        /**
         * Not all passwords are validated in the client (e.g. when logging in).
         * As the password can not be the correct one if it's invalid, we use the same exception message here.
         */
        parent::__construct('Das Passwort ist ungültig.');
    }
}
