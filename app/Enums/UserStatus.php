<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE    = 'active';
    case INACTIVE  = 'inactive';
    case SUSPENDED = 'suspended';
    case BANNED    = 'banned';
    case PENDING   = 'pending';
    case DELETED   = 'deleted';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
