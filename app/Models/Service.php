<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status', // active, inactive
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean', // true = active, false = inactive
    ];
}
