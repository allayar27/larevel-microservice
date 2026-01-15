<?php

namespace App\Enums;

enum UserEvent: string
{
    case CREATED = 'user.created';
    case UPDATED = 'user.updated';
    case DELETED = 'user.deleted';

    public static function fromPayload(array $payload): ?self
    {
        return self::tryFrom(trim(strtolower($payload['event'] ?? '')));
    }
}
