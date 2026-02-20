<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description', // CHANGED THIS from 'content' to 'description'
        'image_path',
        'is_active',
        'expires_at', 
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime', 
    ];
}