<?php

namespace App\Enums;

enum AttachmentType: string
{
    case XRAY = 'xray';
    case ANALYSIS = 'analysis';
    case PHOTO = 'photo';
    case REPORT = 'report';
    case DOCUMENT = 'document';
    case VIDEO = 'video';
    case AUDIO = 'audio';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
