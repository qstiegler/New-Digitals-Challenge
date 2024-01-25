<?php

declare(strict_types=1);

namespace App\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AppTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * @template T
     *
     * @psalm-param class-string<T> $serviceClass
     *
     * @return T
     */
    protected function getContainerService(string $serviceClass): object
    {
        /**
         * @var T
         *
         * @psalm-suppress InvalidReturnStatement
         */
        return static::getContainer()->get($serviceClass);
    }

    protected function getServiceById(string $serviceId): object
    {
        /** @var object */
        return self::getContainer()->get($serviceId);
    }

    protected function assertNoExceptionThrown(): void
    {
        self::assertTrue(true);
    }
}
