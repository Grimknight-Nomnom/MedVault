<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

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
        'email_verified_at', 
        'password',
        'role',
        'phone',
        'address',
        'patient_photo_path',
        'residency_rejection_reason',
        'date_of_birth',
        'gender',
        'civil_status',
        'allergies',
        'current_medication',
        'existing_medical_conditions',
        'is_philhealth_member',
        'is_senior_citizen_or_pwd',
        'parent_id', 
        'has_pregnancy_record', // NEW
        'has_immunization_record', // NEW
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
            'has_pregnancy_record' => 'boolean', // NEW
            'has_immunization_record' => 'boolean', // NEW
        ];
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} " . ($this->middle_name ? "{$this->middle_name} " : "") . $this->last_name;
    }

    public function getAgeAttribute($value)
    {
        if ($this->date_of_birth) {
            $diff = $this->date_of_birth->diff(Carbon::now());
            
            if ($diff->y > 0) {
                return $diff->y . ($diff->y == 1 ? ' year' : ' years');
            } elseif ($diff->m > 0) {
                return $diff->m . ($diff->m == 1 ? ' month' : ' months');
            } elseif ($diff->d > 0) {
                return $diff->d . ($diff->d == 1 ? ' day' : ' days');
            } else {
                return 'Newborn';
            }
        }
        return $value;
    }

    public function getNeedsOwnResidencyAttribute()
    {
        if ($this->parent_id && $this->date_of_birth) {
            $ageInYears = $this->date_of_birth->diffInYears(Carbon::now());
            if ($ageInYears >= 18) {
                if (empty($this->patient_photo_path) || ($this->parent && $this->patient_photo_path === $this->parent->patient_photo_path)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
    // Add this right below your public function parent()
    public function pregnancyRecord()
    {
        return $this->hasOne(PregnancyRecord::class);
    }

    // ADD THIS NEW ONE:
    public function immunizationRecord()
    {
        return $this->hasOne(ImmunizationRecord::class);
    }

    
}