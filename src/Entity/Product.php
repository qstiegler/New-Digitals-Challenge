<?php

namespace App\Entity;

use App\Doctrine\ColumnOptions;
use App\Repository\ProductRepository;
use App\ValueObject\ProductId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'product')]
/** @psalm-immutable */
class Product
{
    #[ORM\Id]
    #[ORM\Column(name: 'product_id', type: 'product_id')]
    /** @psalm-readonly */
    public ProductId $productId;

    #[ORM\Column(name: 'name', type: 'string', options: ColumnOptions::DEFAULT_COLLATION)]
    public string $name;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    /** @psalm-readonly */
    public \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
    public \DateTimeImmutable $updatedAt;

    public function __construct(
        ProductId $productId,
        string $name,
        \DateTimeImmutable $createdAt,
    ) {
        $this->productId = $productId;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
    }
}
