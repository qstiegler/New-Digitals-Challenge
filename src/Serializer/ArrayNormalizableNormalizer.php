<?php

declare(strict_types=1);

namespace App\Serializer;

use App\ValueObject\ArrayNormalizable;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class ArrayNormalizableNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof ArrayNormalizable;
    }

    /** @param class-string $type */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return class_exists($type)
            && is_subclass_of($type, ArrayNormalizable::class);
    }

    /** @param ArrayNormalizable|null $object */
    public function normalize(mixed $object, string $format = null, array $context = []): ?array
    {
        if ($object === null) {
            return null;
        }

        return $object->normalize();
    }

    /**
     * @param array|null                      $data
     * @param class-string<ArrayNormalizable> $type
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): ?ArrayNormalizable
    {
        if ($data === null) {
            return null;
        }

        return $type::denormalize($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ArrayNormalizable::class => true,
        ];
    }
}
