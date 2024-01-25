<?php

declare(strict_types=1);

namespace App\Service\Emails\Exception;

use App\CQRS\Exception\InternalException;

/** @psalm-immutable */
final class TemplateRenderingFailed extends InternalException
{
    public function construct(
        string $template,
    ) {
        parent::__construct(sprintf('Rendering failed for template %s', $template));
    }
}
