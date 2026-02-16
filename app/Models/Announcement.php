<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'is_active',
        'expires_at', // Add this
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime', // Automatically convert to Carbon instance
    ];
}