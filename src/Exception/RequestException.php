<?php

declare(strict_types=1);

namespace App\Exception;

use App\CQRS\Exception\UserFacingException;
use Symfony\Component\HttpFoundation\Response;

/** @psalm-immutable */
final class RequestException extends UserFacingException
{
    public function __construct(string $message, int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $statusCode);
    }
}
