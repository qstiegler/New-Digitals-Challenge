<?php

declare(strict_types=1);

namespace App\Exception;

use App\CQRS\Exception\InternalException;
use Symfony\Component\HttpFoundation\Response;

/** @psalm-immutable */
final class GenericRequestException extends InternalException
{
    /** @psalm-param Response::HTTP_BAD_REQUEST | Response::HTTP_FORBIDDEN | Response::HTTP_NOT_FOUND | Response::HTTP_INTERNAL_SERVER_ERROR $statusCode */
    public function __construct(string $message, int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, $statusCode);
    }
}
