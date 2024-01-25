<?php

declare(strict_types=1);

namespace App\Serializer;

use Doctrine\DBAL\Connection;

final readonly class DecoderConnectionWrapper
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    /** @param array<string, DTO\DecoderOption> $decoderOptions */
    public function fetchAssociative(
        string $query,
        array $params = [],
        array $types = [],
        array $decoderOptions = [],
    ): array {
        /** @var array $result */
        $result = $this->connection->fetchAssociative($query, $params, $types);

        return self::decodeItem($result, $decoderOptions);
    }

    /** @param array<string, DTO\DecoderOption> $decoderOptions */
    public function fetchAllAssociative(
        string $query,
        array $params = [],
        array $types = [],
        array $decoderOptions = [],
    ): array {
        $result = $this->connection->fetchAllAssociative($query, $params, $types);

        return self::decodeResults($result, $decoderOptions);
    }

    public function fetchInt(
        string $query,
        array $params = [],
        array $types = [],
    ): int {
        return (int) $this->connection->fetchOne($query, $params, $types);
    }

    /**
     * @param array<int, array<string, mixed> $data
     * @param array<string, DTO\DecoderOption> $decoderOptions
     */
    public static function decodeResults(
        array $data,
        array $decoderOptions,
    ): array {
        $decodedData = [];
        foreach ($data as $item) {
            $decodedData[] = self::decodeItem($item, $decoderOptions);
        }

        return $decodedData;
    }

    /** @param array<string, DTO\DecoderOption> $decoderOptions */
    private static function decodeItem(
        array $item,
        array $decoderOptions,
    ): array {
        $relevantKeys = array_keys($decoderOptions);

        $decodedItem = [];
        foreach ($item as $itemKey => $itemValue) {
            if (!in_array($itemKey, $relevantKeys, true)) {
                $decodedItem[$itemKey] = $itemValue;
                continue;
            }

            $decoderOption = $decoderOptions[$itemKey];
            $decodedItem[$itemKey] = match ($decoderOption) {
                DTO\DecoderOption::INT => (int) $itemValue,
                DTO\DecoderOption::NULLABLE_INT => $itemValue === null
                    ? null
                    : (int) $itemValue,
                DTO\DecoderOption::FLOAT => (float) $itemValue,
                DTO\DecoderOption::NULLABLE_FLOAT => $itemValue === null
                    ? null
                    : (float) $itemValue,
                DTO\DecoderOption::JSON => json_decode(
                    $itemValue,
                    true,
                    512,
                    \JSON_THROW_ON_ERROR,
                ),
                DTO\DecoderOption::NULLABLE_JSON => $itemValue === null
                    ? null
                    : json_decode(
                        $itemValue,
                        true,
                        512,
                        \JSON_THROW_ON_ERROR,
                    ),
                DTO\DecoderOption::JSON_WITH_EMPTY_ARRAY_ON_NULL => $itemValue === null
                    ? []
                    : json_decode(
                        $itemValue,
                        true,
                        512,
                        \JSON_THROW_ON_ERROR,
                    ),
            };
        }

        return $decodedItem;
    }
}
