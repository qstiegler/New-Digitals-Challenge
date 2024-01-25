<?php

declare(strict_types=1);

namespace App\Domain\Products\WriteSide\CreateProduct;

use Assert\Assertion;
use DigitalCraftsman\CQRS\Command\Command;

final readonly class CreateProductCommand implements Command
{
    public function __construct(
        public string $productName,
        public \DateTimeImmutable $executedAt,
    ) {
        Assertion::notBlank($this->productName);
        Assertion::same($this->productName, trim($this->productName));
        Assertion::maxLength($this->productName, 255);
    }
}
