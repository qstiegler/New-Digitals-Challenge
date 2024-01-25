<?php

declare(strict_types=1);

namespace App\Serializer;

use App\ValueObject\StringNormalizable;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class StringNormalizableNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof StringNormalizable;
    }

    /** @param class-string $type */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return class_exists($type)
            && is_subclass_of($type, StringNormalizable::class);
    }

    /** @param StringNormalizable|null $object */
    public function normalize(mixed $object, string $format = null, array $context = []): ?string
    {
        if ($object === null) {
            return null;
        }

        return $object->normalize();
    }

    /**
     * @param string|null                      $data
     * @param class-string<StringNormalizable> $type
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): ?StringNormalizable
    {
        if ($data === null) {
            return null;
        }

        return $type::denormalize($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            StringNormalizable::class => true,
        ];
    }
}
