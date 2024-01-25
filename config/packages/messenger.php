<?php

use App\Messenger\MessengerIdMiddleware;
use App\Messenger\SendEmail\SendEmailMessage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\FrameworkConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

const SYNC_QUEUE = 'sync';
const GENERAL_QUEUE = 'general';
const FAILED_QUEUE = 'failed';

return static function (FrameworkConfig $framework, ContainerConfigurator $container) {
    $messenger = $framework->messenger();

    // -- Serializer
    $messenger
        ->serializer()
        ->defaultSerializer('messenger.transport.symfony_serializer')
        ->symfonySerializer()
            ->format('json');

    // -- Failure transport
    $messenger->failureTransport(FAILED_QUEUE);

    // -- Middleware
    $defaultBus = $messenger->bus('messenger.bus.default');
    $defaultBus->middleware()->id(MessengerIdMiddleware::class);

    $messenger->defaultBus('messenger.bus.default');

    // -- Transports

    // Sync - Triggered without queue
    $messenger
        ->transport(SYNC_QUEUE)
        ->dsn('sync://')
    ;

    // General - General queue for async messages
    $messenger
        ->transport(GENERAL_QUEUE)
        ->dsn('doctrine://default')
        ->options([
            'queue_name' => GENERAL_QUEUE,
            'use_notify' => false,
        ])
        ->retryStrategy()
            ->maxRetries(env('int:MESSENGER_RETRY_MAX_RETRIES'))
            ->delay(env('int:MESSENGER_RETRY_DELAY'))
            ->multiplier(2)
    ;

    // Failed - Failed buckets for errors
    $messenger
        ->transport(FAILED_QUEUE)
        ->dsn('doctrine://default')
        ->options([
            'queue_name' => FAILED_QUEUE,
        ])
    ;

    // -- Default Routing

    $messenger->routing(SendEmailMessage::class)->senders([GENERAL_QUEUE]);

    // -- Dev routing

    if ($container->env() === 'dev') {
        $messenger->routing(SendEmailMessage::class)->senders([SYNC_QUEUE]);
    }

    // -- Test routing

    if ($container->env() === 'test') {
        $messenger->routing(SendEmailMessage::class)->senders([SYNC_QUEUE]);
    }
};
