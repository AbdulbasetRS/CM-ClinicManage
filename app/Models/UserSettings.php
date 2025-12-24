<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;

    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',
        'language',
        'theme',
        'font_size',
        'enable_two_factor',
        'google2fa_secret',
        'timezone',
        'date_format',
        'time_format',
        'notifications_email',
        'notifications_sound',
        'login_alerts',
        'currency',
    ];

    protected $casts = [
        'enable_two_factor' => 'boolean',
        'notifications_email' => 'boolean',
        'notifications_sound' => 'boolean',
        'login_alerts' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the settings
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
