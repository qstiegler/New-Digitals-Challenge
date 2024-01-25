<?php

declare(strict_types=1);

namespace App\Messenger\SendEmail\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class AttachmentNotFound extends InternalException
{
    public function __construct(string $internalFilePath)
    {
        parent::__construct(sprintf('Attachment on path %s can not be found', $internalFilePath));
    }
}
