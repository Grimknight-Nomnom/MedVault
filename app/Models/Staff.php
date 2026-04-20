<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'picture_path',
        'is_active',           // NEW: Track if staff is active
        'inactive_reason',     // NEW: Reason for deactivation
        'deactivated_at',      // NEW: When staff was deactivated
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deactivated_at' => 'datetime',
    ];

    /**
     * Scope to get only active staff
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only inactive staff
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}