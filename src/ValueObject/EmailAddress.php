<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\ValueObject\Exception\EmailAddressInvalid;
use App\ValueObject\Exception\EmailAddressIsEqual;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\NoRFCWarningsValidation;

/** @psalm-immutable */
final class EmailAddress implements \Stringable, StringNormalizable
{
    public string $emailAddress;

    // Construction

    public function __construct(string $emailAddress)
    {
        if (!self::isValid($emailAddress)) {
            throw new EmailAddressInvalid($emailAddress);
        }

        $this->emailAddress = $emailAddress;
    }

    // Magic

    public function __toString(): string
    {
        return $this->emailAddress;
    }

    // String normalizable

    public static function denormalize(string $string): StringNormalizable
    {
        return new self($string);
    }

    public function normalize(): string
    {
        return $this->emailAddress;
    }

    // Guards

    public function mustNotBeEqualTo(self $emailAddress): void
    {
        if ($this->isEqualTo($emailAddress)) {
            throw new EmailAddressIsEqual($this);
        }
    }

    // Accessors

    public function isEqualTo(self $emailAddress): bool
    {
        return $this->emailAddress === $emailAddress->emailAddress;
    }

    public function isNotEqualTo(self $emailAddress): bool
    {
        return !$this->isEqualTo($emailAddress);
    }

    public static function isValid(string $emailAddress): bool
    {
        $validator = new EmailValidator();

        // We don't allow for uppercase characters
        if (1 === preg_match('/[A-Z]/', $emailAddress)) {
            return false;
        }

        // Validate email against RFC standards
        return $validator->isValid(
            $emailAddress,
            new NoRFCWarningsValidation(),
        );
    }
}
