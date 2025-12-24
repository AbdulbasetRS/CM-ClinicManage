<?php

namespace App\Helpers;

class NotificationModelUrl
{
    /**
     * Return URL for a notification based on its type + the provided parameter.
     */
    public static function getUrl(string $notificationType, $param): ?string
    {
        $map = [
            \App\Notifications\NewUserCreated::class => 'admin.users.show',
            \App\Models\User::class => 'admin.users.show',
        ];

        // لو النوع مش موجود في الماب
        if (! isset($map[$notificationType])) {
            return null;
        }

        // لو مفيش param مبعوت
        if (! $param) {
            return null;
        }

        return route($map[$notificationType], $param);
    }
}
