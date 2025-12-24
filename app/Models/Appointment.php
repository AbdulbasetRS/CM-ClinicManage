<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => AppointmentStatus::class,
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'datetime',
    ];

    // ====== Relations ======

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function visit()
    {
        return $this->hasOne(Visit::class);
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
