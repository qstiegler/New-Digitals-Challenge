<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class Denormalizer
{
    public function __construct(
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    public function denormalize(
        array $data,
        string $class,
    ): object {
        /** @var T */
        return $this->denormalizer->denormalize(
            data: $data,
            type: $class,
        );
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return array<int, T>
     */
    public function denormalizeArray(
        array $data,
        string $class,
    ): array {
        /** @var array<int, T> */
        return $this->denormalizer->denormalize(
            data: $data,
            type: self::arrayOfClass($class),
        );
    }

    public static function arrayOfClass(string $class): string
    {
        return sprintf('%s[]', $class);
    }
}
