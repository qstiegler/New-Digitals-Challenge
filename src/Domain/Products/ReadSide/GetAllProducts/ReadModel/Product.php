<?php

declare(strict_types=1);

namespace App\Domain\Products\ReadSide\GetAllProducts\ReadModel;

use App\ValueObject\ProductId;

final readonly class Product
{
    public function __construct(
        public ProductId $productId,
        public string $name,
    ) {
    }
}
