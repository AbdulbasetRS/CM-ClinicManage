<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DateHelper
{
    /**
     * Convert a UTC datetime to a specific timezone.
     *
     * Example usage:
     * ```php
     * $utcTime = '2025-11-09 02:00:00';
     * $cairoTime = DateHelper::toTimezone($utcTime, 'UTC');
     * echo $cairoTime; // Carbon instance in 'UTC' timezone
     * ```
     *
     * @param  string|Carbon|null  $utcDateTime  The datetime in UTC or a Carbon instance.
     * @param  string  $timezone  The target timezone (default: 'UTC').
     * @return Carbon|null Returns a Carbon instance in the target timezone or null if input is empty.
     */
    public static function toTimezone($utcDateTime, $timezone = 'UTC')
    {
        if (empty($utcDateTime)) {
            return null;
        }

        return Carbon::parse($utcDateTime, 'UTC')->setTimezone($timezone);
    }

    /**
     * Format a datetime to a custom format.
     * Accepts both UTC strings or Carbon instances.
     *
     * Example usage:
     * ```php
     * $utcTime = '2025-11-09 02:00:00';
     * $cairoTime = DateHelper::toTimezone($utcTime, 'UTC');
     * $formatted = DateHelper::formatDateTime($cairoTime, 'd M Y - h:i A');
     * echo $formatted; // "09 Nov 2025 - 04:00 AM"
     * ```
     *
     * @param  string|Carbon|null  $dateTime  The datetime to format.
     * @param  string  $format  The desired format (default: 'd M Y - h:i A').
     * @return string|null Returns the formatted datetime as a string or null if input is empty.
     */
    public static function formatDateTime($dateTime, $format = 'd M Y - h:i A')
    {
        if (empty($dateTime)) {
            return null;
        }

        if (! ($dateTime instanceof Carbon)) {
            $dateTime = Carbon::parse($dateTime);
        }

        return $dateTime->translatedFormat($format);
    }

    /**
     * Get the difference between the given datetime and now in a human-readable format.
     *
     * Example usage:
     * ```php
     * $utcTime = '2025-11-09 02:00:00';
     * echo DateHelper::diffForHumans($utcTime, 'UTC'); // "3 hours ago" or similar
     * ```
     *
     * @param  string|Carbon|null  $dateTime  The datetime to compare with now.
     * @param  string  $timezone  The timezone to convert to before calculating the difference (default: 'UTC').
     * @return string|null Returns the difference in human-readable format or null if input is empty.
     */
    public static function diffForHumans($dateTime, $timezone = 'UTC')
    {
        $dateTime = self::toTimezone($dateTime, $timezone);
        if (! $dateTime) {
            return null;
        }

        return $dateTime->diffForHumans();
    }

    /**
     * Convert a UTC datetime to a specific timezone and format it in one step.
     *
     * Example usage:
     * ```php
     * $utcTime = '2025-11-09 02:00:00';
     * $formatted = DateHelper::convertAndFormat($utcTime, 'UTC', 'Y-m-d H:i:s');
     * echo $formatted; // "2025-11-09 04:00:00"
     * ```
     *
     * @param  string|Carbon|null  $utcDateTime  The datetime in UTC or a Carbon instance.
     * @param  string  $timezone  The target timezone (default: 'UTC').
     * @param  string  $format  The desired format after conversion (default: 'Y-m-d H:i:s').
     * @return string|null Returns the converted and formatted datetime as a string or null if input is empty.
     */
    public static function convertAndFormat($utcDateTime, $timezone = 'UTC', $format = 'Y-m-d H:i:s')
    {
        $dateTime = self::toTimezone($utcDateTime, $timezone);
        if (! $dateTime) {
            return null;
        }

        return self::formatDateTime($dateTime, $format);
    }

    /**
     * Get the timezone for the current authenticated user or fallback to app timezone.
     *
     * This method checks if the user has a custom timezone set in the database.
     * If not found or user is not authenticated, it returns the application's default timezone.
     *
     * Example usage:
     * ```php
     * $userTimezone = DateHelper::getUserTimezone();
     * echo $userTimezone; // "Africa/Cairo" or "UTC" (app default)
     * ```
     *
     * @return string Returns the user's timezone or app default timezone
     */
    public static function getUserTimezone(): string
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // If user is authenticated and has a timezone set, return it
        // Note: You'll need to add 'timezone' column to users table for this to work
        if ($user && isset($user->userSettings->timezone) && ! empty($user->userSettings->timezone)) {
            return $user->userSettings->timezone;
        }

        // Fallback to application's default timezone
        return config('app.timezone', 'UTC');
    }

    /**
     * Get the user's preferred date format.
     *
     * @return string
     */
    public static function getUserDateFormat()
    {
        if (Auth::check() && Auth::user()->userSettings && Auth::user()->userSettings->date_format) {
            return Auth::user()->userSettings->date_format;
        }

        return 'Y-m-d';
    }

    /**
     * Get the user's preferred time format.
     *
     * @return string
     */
    public static function getUserTimeFormat()
    {
        if (Auth::check() && Auth::user()->userSettings && Auth::user()->userSettings->time_format) {
            return Auth::user()->userSettings->time_format;
        }

        return '12h';
    }

    /**
     * Get the combined date and time format for the authenticated user.
     *
     * @return string
     */
    public static function getUserDateTimeFormat()
    {
        $dateFormat = self::getUserDateFormat();
        $timeFormatPref = self::getUserTimeFormat();

        // Convert 12h/24h preference to PHP time format
        $timeFormat = ($timeFormatPref === '24h') ? 'H:i' : 'h:i A';

        return "$dateFormat $timeFormat";
    }
}
