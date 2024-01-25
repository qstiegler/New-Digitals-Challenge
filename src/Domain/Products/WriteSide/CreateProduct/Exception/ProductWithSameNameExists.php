<?php

declare(strict_types=1);

namespace App\Domain\Products\WriteSide\CreateProduct\Exception;

use App\CQRS\Exception\UserFacingException;

/** @psalm-immutable */
final class ProductWithSameNameExists extends UserFacingException
{
    public function __construct(string $productName)
    {
        parent::__construct(sprintf('A product with the name %s already exists', $productName));
    }
}
