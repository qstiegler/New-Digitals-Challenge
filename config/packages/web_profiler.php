<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Disables the symfony web profiler depending on env variable.
 * This needs to be done via PHP configuration @see https://github.com/symfony/symfony/issues/27526.
 */
return static function (ContainerConfigurator $container) {
    $isProfilerEnabled = filter_var($_ENV['IS_PROFILER_ENABLED'], FILTER_VALIDATE_BOOLEAN);

    $container->extension('framework', [
        'profiler' => [
            'enabled' => $isProfilerEnabled,
            'only_exceptions' => false,
        ],
    ]);
};
