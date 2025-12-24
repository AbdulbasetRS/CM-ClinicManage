<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'slug',
        'email',
        'mobile_number',
        'national_id',
        'nationality',
        'password',
        'status',
        'type',
        'can_login',
        'status_details',
        'role_id',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => UserStatus::class,
        'type' => UserType::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = [
        'profile',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function authProviders()
    {
        return $this->hasMany(AuthProvider::class, 'user_id', 'id');
    }

    public function userSettings()
    {
        return $this->hasOne(UserSettings::class, 'user_id', 'id');
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->userSettings
            && $this->userSettings->enable_two_factor
            && ! empty($this->userSettings->google2fa_secret);
    }

    public function isAdmin(): bool
    {
        return $this->type->value === 'admin';
    }

    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    // كمريض
    public function visitsAsPatient()
    {
        return $this->hasMany(Visit::class, 'patient_id');
    }

    // كدكتور
    public function visitsAsDoctor()
    {
        return $this->hasMany(Visit::class, 'doctor_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'patient_id');
    }

    public function uploadedAttachments()
    {
        return $this->hasMany(Attachment::class, 'uploaded_by');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'patient_id');
    }

    public function patient()
    {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }

    public function doctor()
    {
        return $this->hasOne(User::class, 'id', 'doctor_id');
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
