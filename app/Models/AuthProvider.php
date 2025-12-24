<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthProvider extends Model
{
    use HasFactory;

    protected $table = 'auth_providers';

    protected $fillable = [
        'user_id',
        'provider_name',
        'provider_user_id',
        'provider_access_token',
        'refresh_token',
        'token_expires_at',
        'email',
        'name',
        'avatar',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
    ];

    protected $hidden = [

    ];

    function getRouteKeyName()
    {
        return 'id';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
