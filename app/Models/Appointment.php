<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'appointment_date',
        'status',
        'reason'
    ];

    protected $casts = [
        'appointment_date' => 'datetime', // Handles the date formatting automatically
    ];

    // -- Relationships --

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}