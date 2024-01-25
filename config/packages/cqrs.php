<?php

declare(strict_types=1);

use App\CQRS\HandlerWrapper\ConnectionTransactionWrapper;
use DigitalCraftsman\CQRS\DTOConstructor\SerializerDTOConstructor;
use DigitalCraftsman\CQRS\RequestDecoder\JsonRequestDecoder;
use DigitalCraftsman\CQRS\ResponseConstructor\EmptyJsonResponseConstructor;
use DigitalCraftsman\CQRS\ResponseConstructor\SerializerJsonResponseConstructor;
use Symfony\Config\CqrsConfig;

return static function (CqrsConfig $cqrsConfig) {
    // -- Query controller

    $cqrsConfig->queryController()
        ->defaultRequestDecoderClass(JsonRequestDecoder::class)
        ->defaultDtoConstructorClass(SerializerDTOConstructor::class)
        ->defaultResponseConstructorClass(SerializerJsonResponseConstructor::class);

    // -- Command controller

    $cqrsConfig->commandController()
        ->defaultRequestDecoderClass(JsonRequestDecoder::class)
        ->defaultDtoConstructorClass(SerializerDTOConstructor::class)
        ->defaultHandlerWrapperClasses([
            ConnectionTransactionWrapper::class => null,
        ])
        ->defaultResponseConstructorClass(EmptyJsonResponseConstructor::class);
};
