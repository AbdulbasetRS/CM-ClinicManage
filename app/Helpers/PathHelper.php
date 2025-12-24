<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PathHelper
{
    /*
    |--------------------------------------------------------------------------
    | 🧩 Base Utilities
    |--------------------------------------------------------------------------
    */
    protected static function makePath(string $type, int|string $id, string $folder, string $filename = ''): string
    {
        $path = "{$type}s/{$id}/{$folder}";

        return $filename ? "{$path}/{$filename}" : $path;
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Cloud
     */
    protected static function disk()
    {
        return Storage::disk('public');
    }

    /*
    |--------------------------------------------------------------------------
    | 👤 User Methods
    |--------------------------------------------------------------------------
    */

    // 📍 تحديد مسار الصورة أو المجلد
    public static function userAvatarPath(int|string $userId, string $filename = ''): string
    {
        return self::makePath('user', $userId, 'avatars', $filename);
    }

    // 💾 تخزين الصورة
    public static function storeUserAvatar(int|string $userId, UploadedFile $file): string
    {
        $filename = now()->format('Ymd_His').'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
        $path = self::userAvatarPath($userId, $filename);

        self::disk()->putFileAs(dirname($path), $file, $filename);

        return $filename; // نرجع اسم الملف فقط
    }

    // 🌐 الحصول على URL للصورة
    public static function userAvatarUrl(int|string $userId, string $filename): string
    {
        $path = self::userAvatarPath($userId, $filename);

        if (app()->environment('local')) {
            return asset('storage/'.$path);
        }

        return self::disk()->url($path);
    }

    // 🗑️ حذف الصورة
    public static function deleteUserAvatar(int|string $userId, string $filename): bool
    {
        $path = self::userAvatarPath($userId, $filename);

        if (self::disk()->exists($path)) {
            return self::disk()->delete($path);
        }

        return false;
    }

    // 📁 مسار ملفات media (لو عايزها لاحقًا)
    public static function userMediaPath(int|string $userId, string $filename = ''): string
    {
        return self::makePath('user', $userId, 'media', $filename);
    }

    public static function storeUserMedia(int|string $userId, UploadedFile $file): string
    {
        $filename = now()->format('Ymd_His').'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
        $path = self::userMediaPath($userId, $filename);

        self::disk()->putFileAs(dirname($path), $file, $filename);

        return $filename;
    }

    public static function userMediaUrl(int|string $userId, string $filename): string
    {
        return self::disk()->url(self::userMediaPath($userId, $filename));
    }

    public static function deleteUserMedia(int|string $userId, string $filename): bool
    {
        $path = self::userMediaPath($userId, $filename);

        if (self::disk()->exists($path)) {
            return self::disk()->delete($path);
        }

        return false;
    }
}
