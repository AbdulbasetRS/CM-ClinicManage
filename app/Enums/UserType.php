<?php

namespace App\Enums;

enum UserType: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case IT = 'it';
    case TESTER = 'tester';
    case EMPLOYEE = 'employee';
    case DOCTOR = 'doctor';
    case ASSISTANT = 'assistant';
    case RECEPTIONIST = 'receptionist';
    case PATIENT = 'patient';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
