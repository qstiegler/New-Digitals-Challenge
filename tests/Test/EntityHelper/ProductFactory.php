<?php

declare(strict_types=1);

namespace App\Test\EntityHelper;

use App\Entity\Product;
use App\Time\Clock;
use App\ValueObject\ProductId;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ProductFactory
{
    public function __construct(
        private Clock $clock,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(
        ?ProductId $productId = null,
        ?string $productName = null,
        ?\DateTimeImmutable $createdAt = null,
    ): Product {
        $productId = $productId ?? ProductId::generateRandom();
        $productName = $productName ?? 'Test Product';
        $createdAt = $createdAt ?? $this->clock->now();

        $product = new Product(
            $productId,
            $productName,
            $createdAt,
        );

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }
}
