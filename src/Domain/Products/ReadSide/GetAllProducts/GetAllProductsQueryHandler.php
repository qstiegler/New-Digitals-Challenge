<?php

declare(strict_types=1);

namespace App\Domain\Products\ReadSide\GetAllProducts;

use App\Repository\ProductRepository;
use DigitalCraftsman\CQRS\Query\QueryHandlerInterface;

final readonly class GetAllProductsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    /** @return list<ReadModel\Product> */
    public function __invoke(GetAllProductsQuery $query): array
    {
        return $this->getAllProductsAsReadModels();
    }

    /** @return list<ReadModel\Product> */
    private function getAllProductsAsReadModels(): array
    {
        $products = $this->productRepository->findAll();

        return array_map(
            static fn ($product) => new ReadModel\Product(
                $product->productId,
                $product->name,
            ),
            $products,
        );
    }
}
