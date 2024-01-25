<?php

declare(strict_types=1);

namespace App\Domain\Products\WriteSide\CreateProduct;

use App\Domain\Products\WriteSide\CreateProduct\Exception\ProductWithSameNameExists;
use App\Repository\ProductRepository;
use App\Test\EntityHelper\ProductFactory;
use App\TestCase\AppTestCase;
use App\Time\TestClock;

final class CreateProductCommandHandlerTest extends AppTestCase
{
    private TestClock $clock;
    private ProductFactory $productFactory;
    private ProductRepository $productRepository;
    private CreateProductCommandHandler $commandHandler;

    protected function setUp(): void
    {
        $this->clock = $this->getContainerService(TestClock::class);
        $this->productFactory = $this->getContainerService(ProductFactory::class);
        $this->productRepository = $this->getContainerService(ProductRepository::class);
        $this->commandHandler = $this->getContainerService(CreateProductCommandHandler::class);
    }

    /**
     * @test
     *
     * @covers ::__invoke
     */
    public function create_product_works(): void
    {
        // -- Arrange
        $executedAt = $this->clock->now();

        $command = new CreateProductCommand('Unique Product', $executedAt);

        // -- Act
        $this->commandHandler->__invoke($command);

        // -- Assert
        $products = $this->productRepository->findAll();

        self::assertCount(1, $products);
        self::assertEquals('Unique Product', $products[0]->name);
        self::assertEquals($executedAt, $products[0]->createdAt);
        self::assertEquals($executedAt, $products[0]->updatedAt);
    }

    /**
     * @test
     *
     * @covers ::__invoke
     */
    public function create_with_existing_name_product_fails(): void
    {
        // -- Assert
        $this->expectException(ProductWithSameNameExists::class);

        // -- Arrange
        $executedAt = $this->clock->now();
        $existingProductName = 'Existing Product';
        $this->productFactory->create(
            productName: $existingProductName,
        );

        $command = new CreateProductCommand($existingProductName, $executedAt);

        // -- Act
        $this->commandHandler->__invoke($command);
    }
}
