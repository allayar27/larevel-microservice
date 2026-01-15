<?php

namespace App\Enums;

enum ProductEvent: string
{
    case CREATED = 'product.created';
    case UPDATED = 'product.updated';
    case DELETED = 'product.deleted';

    public static function fromPayload(array $payload): ?self
    {
        return self::tryFrom(trim(strtolower($payload['event'] ?? '')));
    }
}
