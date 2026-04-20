<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PregnancyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lmp',
        'edc',
        'edd',
        'gravida',
        'para',
        'fetal_heart_beat',
        'blood_pressure',
        'weight',
        'visit_1_date', 'visit_1_weight', 'visit_1_bp', 'visit_1_fundal_height', 'visit_1_fetal_heart_beat',
        'visit_2_date', 'visit_2_weight', 'visit_2_bp', 'visit_2_fundal_height', 'visit_2_fetal_heart_beat',
        'visit_3_date', 'visit_3_weight', 'visit_3_bp', 'visit_3_fundal_height', 'visit_3_fetal_heart_beat',
        'visit_4_date', 'visit_4_weight', 'visit_4_bp', 'visit_4_fundal_height', 'visit_4_fetal_heart_beat',
        'visit_5_date', 'visit_5_weight', 'visit_5_bp', 'visit_5_fundal_height', 'visit_5_fetal_heart_beat',
        'visit_6_date', 'visit_6_weight', 'visit_6_bp', 'visit_6_fundal_height', 'visit_6_fetal_heart_beat',
        'tt1_date', 'tt2_date', 'tt3_date', 'tt4_date', 'tt5_date',
        'iron_folic_1_date', 'iron_folic_2_date', 'iron_folic_3_date', 'iron_folic_4_date', 'iron_folic_5_date', 'iron_folic_6_date',
        'birth_plan_date', 'bemonc_cemonc', 'is_completed' // <-- ADD THIS HERE
    ];

    protected $casts = [
        'bemonc_cemonc' => 'boolean',
        'is_completed' => 'boolean' // <-- AND ADD THIS HERE
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}