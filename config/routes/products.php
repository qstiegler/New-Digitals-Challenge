<?php

use App\CQRS\RequestDataTransformer\AddExecutedAtDataTransformer;
use App\Domain\Products\ReadSide\GetAllProducts\GetAllProductsQuery;
use App\Domain\Products\ReadSide\GetAllProducts\GetAllProductsQueryHandler;
use App\Domain\Products\WriteSide\CreateProduct\CreateProductCommand;
use App\Domain\Products\WriteSide\CreateProduct\CreateProductCommandHandler;
use DigitalCraftsman\CQRS\Routing\RouteBuilder;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

// -- Admin notifications

return static function (RoutingConfigurator $routes) {
    // -- Queries

    RouteBuilder::addQueryRoute(
        $routes,
        path: '/api/products/get-all-products-query',
        dtoClass: GetAllProductsQuery::class,
        handlerClass: GetAllProductsQueryHandler::class,
    );

    // -- Commands

    RouteBuilder::addCommandRoute(
        $routes,
        path: '/api/products/create-product-command',
        dtoClass: CreateProductCommand::class,
        handlerClass: CreateProductCommandHandler::class,
        requestDataTransformerClasses: [
            AddExecutedAtDataTransformer::class => null,
        ],
    );
};
