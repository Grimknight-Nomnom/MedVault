<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmunizationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_time',
        'birth_weight',
        'birth_length',
        'eye_color',
        'hair_color',
        'birth_hospital',
        'mother_name',
        'father_name',
        'is_completed', // <-- ADD THIS
    ];

    protected $casts = [
        'is_completed' => 'boolean', // <-- ADD THIS
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}