<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointment_date',
        'queue_number',
        'status',
        'reason'
    ];

    // This converts the database date string into a Carbon object automatically
    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    // -- Relationships --

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }
}