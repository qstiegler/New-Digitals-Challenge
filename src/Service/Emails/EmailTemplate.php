<?php

declare(strict_types=1);

namespace App\Service\Emails;

use App\Service\Emails\Exception\TemplateRenderingFailed;
use Twig\Environment as Twig;
use Twig\Error\Error as TwigError;

final readonly class EmailTemplate
{
    public function __construct(
        private Twig $twig,
    ) {
    }

    public function renderBody(
        string $template,
        array $parameters = [],
    ): string {
        try {
            return $this->twig->render($template, $parameters);
        } catch (TwigError) {
            throw new TemplateRenderingFailed($template);
        }
    }
}
