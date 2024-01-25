<?php

declare(strict_types=1);

namespace App\CQRS\Exception;

/** @psalm-suppress MutableDependency */
use Symfony\Component\HttpFoundation\Response;

/** @psalm-immutable */
class InternalException extends \DomainException
{
    public int $statusCode;

    /** @psalm-param Response::HTTP_BAD_REQUEST | Response::HTTP_UNAUTHORIZED, | Response::HTTP_FORBIDDEN | Response::HTTP_NOT_FOUND | Response::HTTP_INTERNAL_SERVER_ERROR $statusCode */
    public function __construct(string $message, int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message);

        $this->statusCode = $statusCode;
    }
}
