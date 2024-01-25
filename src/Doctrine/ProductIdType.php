<?php

declare(strict_types=1);

namespace App\Doctrine;

use App\ValueObject\ProductId;
use DigitalCraftsman\Ids\Doctrine\IdType;

final class ProductIdType extends IdType
{
    public static function getTypeName(): string
    {
        return 'product_id';
    }

    public static function getClass(): string
    {
        return ProductId::class;
    }
}
