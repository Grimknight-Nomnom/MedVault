<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSetting extends Model
{
    use HasFactory;

    // IMPORTANT: Add 'label' here so it saves to the database
    protected $fillable = [
        'date', 
        'max_appointments', 
        'is_closed', 
        'label' 
    ];
}