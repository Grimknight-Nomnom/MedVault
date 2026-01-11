<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_name',
        'action_type',
        'quantity_changed',
        'description',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];
}