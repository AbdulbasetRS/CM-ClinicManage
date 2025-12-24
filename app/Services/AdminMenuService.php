<?php

namespace App\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class AdminMenuService
{
    public static function all()
    {
        $menu = collect(config('admin-menu'))
            ->filter(fn ($item) => self::checkPermission($item))
            ->sortBy('order')
            ->map(fn ($item) => self::formatItem($item))
            ->values();

        return $menu;
    }

    private static function checkPermission($item): bool
    {
        if (!isset($item['permission'])) {
            return true;
        }

        return Gate::allows($item['permission']);
    }

    private static function formatItem($item)
    {
        $item['title'] = __($item['title']);
        $item['active'] = self::isActive($item);

        if (isset($item['children'])) {
            $item['children'] = collect($item['children'])
                ->filter(fn ($child) => self::checkPermission($child))
                ->sortBy('order')
                ->map(fn ($child) => self::formatItem($child))
                ->values()
                ->toArray();
        }

        return $item;
    }

    private static function isActive($item): bool
    {
        if (isset($item['route']) && Route::is($item['route'])) {
            return true;
        }

        if (isset($item['children'])) {
            foreach ($item['children'] as $child) {
                if (isset($child['route']) && Route::is($child['route'])) {
                    return true;
                }
            }
        }

        return false;
    }
}
