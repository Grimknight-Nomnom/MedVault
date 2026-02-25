<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PregnancyRecord extends Model
{
    use HasFactory;

    // This allows all columns to be mass-assigned automatically
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}