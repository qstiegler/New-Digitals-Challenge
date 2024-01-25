<?php

declare(strict_types=1);

namespace App\Serializer\DTO;

enum DecoderOption: string
{
    case INT = 'INT';
    case NULLABLE_INT = 'NULLABLE_INT';
    case FLOAT = 'FLOAT';
    case NULLABLE_FLOAT = 'NULLABLE_FLOAT';
    case JSON = 'JSON';
    case NULLABLE_JSON = 'NULLABLE_JSON';
    case JSON_WITH_EMPTY_ARRAY_ON_NULL = 'JSON_WITH_EMPTY_ARRAY_ON_NULL';
}
