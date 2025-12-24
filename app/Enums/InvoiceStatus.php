<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case PAID = 'paid';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';
    case INACTIVE = 'inactive';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
