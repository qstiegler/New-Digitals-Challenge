<?php

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $framework->cache()
        ->defaultPdoProvider('doctrine.dbal.default_connection');

    $framework->cache()
        ->pool('rate_limit.cache')
            ->adapters(['cache.adapter.doctrine_dbal']);
};
