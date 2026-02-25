<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('immunization_records', function (Blueprint $table) {
            // Birth Details
            $table->time('birth_time')->nullable();
            $table->string('birth_weight')->nullable();
            $table->string('birth_length')->nullable();
            $table->string('eye_color')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('birth_hospital')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();

            // Visit Details
            $table->date('visit_date')->nullable();
            $table->string('visit_age')->nullable();
            $table->string('visit_temp')->nullable();
            $table->string('visit_weight')->nullable();
            $table->string('visit_length')->nullable();
            $table->string('visit_hc')->nullable();
            $table->string('visit_cc')->nullable();
            $table->string('visit_ac')->nullable();
            
            // Vaccine & Instructions
            $table->string('type_of_bakuna')->nullable();
            $table->text('doctor_instructions')->nullable();
            $table->date('next_visit')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('immunization_records', function (Blueprint $table) {
            $table->dropColumn([
                'birth_time', 'birth_weight', 'birth_length', 'eye_color', 'hair_color', 
                'birth_hospital', 'mother_name', 'father_name', 'visit_date', 'visit_age', 
                'visit_temp', 'visit_weight', 'visit_length', 'visit_hc', 'visit_cc', 
                'visit_ac', 'type_of_bakuna', 'doctor_instructions', 'next_visit'
            ]);
        });
    }
};