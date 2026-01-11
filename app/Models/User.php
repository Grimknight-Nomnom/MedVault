<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'age',
        'usernumber',
        'email',
        'password',
        'role',
        'phone',
        'address',
        // New Profile Fields
        'date_of_birth',
        'gender',
        'civil_status',
        'allergies',
        'current_medication',
        'existing_medical_conditions',
        'is_philhealth_member',
        'is_senior_citizen_or_pwd',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_philhealth_member' => 'boolean',
            'is_senior_citizen_or_pwd' => 'boolean',
        ];
    }

    // Helper to get full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} " . ($this->middle_name ? "{$this->middle_name} " : "") . $this->last_name;
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}