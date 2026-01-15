<?php

namespace App\Enums;

enum ProductEvent: string
{
    case ORDER_CREATED = 'order.created';

    public static function fromPayload(array $payload): ?self
    {
        return self::tryFrom(trim(strtolower($payload['event'] ?? '')));
    }
}
