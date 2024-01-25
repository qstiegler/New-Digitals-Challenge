<?php

declare(strict_types=1);

namespace App\CQRS\Exception;

use Symfony\Component\HttpFoundation\Response;

/** @psalm-immutable */
abstract class UserFacingException extends InternalException
{
    public int $statusCode;

    /**
     * @psalm-param Response::HTTP_BAD_REQUEST | Response::HTTP_FORBIDDEN | Response::HTTP_NOT_FOUND | Response::HTTP_INTERNAL_SERVER_ERROR | Response::HTTP_UNAUTHORIZED, $statusCode
     */
    public function __construct(
        string $message,
        int $statusCode = Response::HTTP_BAD_REQUEST,
    ) {
        parent::__construct($message);

        $this->statusCode = $statusCode;
    }
}
