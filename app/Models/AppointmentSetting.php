<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSetting extends Model
{
    use HasFactory;

    // Added 'label' to the list
    protected $fillable = ['date', 'max_appointments', 'is_closed', 'label'];
}