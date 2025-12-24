<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case CARD = 'card';
    case INSURANCE = 'insurance';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
