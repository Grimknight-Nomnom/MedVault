<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'category',
        'stock_quantity',
        'price',
        'expiry_date'
    ];

    protected $casts = [
        'expiry_date' => 'date', // Automatically converts DB date to Carbon instance
    ];
}