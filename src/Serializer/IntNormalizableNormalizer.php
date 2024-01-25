<?php

declare(strict_types=1);

namespace App\Serializer;

use App\ValueObject\IntNormalizable;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class IntNormalizableNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof IntNormalizable;
    }

    /** @param class-string $type */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return class_exists($type)
            && is_subclass_of($type, IntNormalizable::class);
    }

    /** @param IntNormalizable|null $object */
    public function normalize(mixed $object, string $format = null, array $context = []): ?int
    {
        if ($object === null) {
            return null;
        }

        return $object->normalize();
    }

    /**
     * @param int|null                      $data
     * @param class-string<IntNormalizable> $type
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): ?IntNormalizable
    {
        if ($data === null) {
            return null;
        }

        return $type::denormalize($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            IntNormalizable::class => true,
        ];
    }
}
