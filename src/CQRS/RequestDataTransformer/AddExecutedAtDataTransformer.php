<?php

declare(strict_types=1);

namespace App\CQRS\RequestDataTransformer;

use App\Time\ClockInterface;
use DigitalCraftsman\CQRS\RequestDataTransformer\RequestDataTransformerInterface;

final readonly class AddExecutedAtDataTransformer implements RequestDataTransformerInterface
{
    public function __construct(
        private ClockInterface $clock,
    ) {
    }

    /** @param null $parameters */
    public function transformRequestData(
        string $dtoClass,
        array $requestData,
        mixed $parameters,
    ): array {
        $requestData['executedAt'] = $this->clock->now()->format(\DateTimeInterface::ATOM);

        return $requestData;
    }

    /** @param null $parameters */
    public static function areParametersValid(mixed $parameters): bool
    {
        return $parameters === null;
    }
}
