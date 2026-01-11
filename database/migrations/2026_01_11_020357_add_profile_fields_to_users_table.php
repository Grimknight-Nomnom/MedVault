<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Demographics (first_name, middle, last, age exist from previous migrations)
            $table->date('date_of_birth')->nullable()->after('age');
            $table->string('gender')->nullable()->after('date_of_birth'); // Male, Female, etc.
            $table->string('civil_status')->nullable()->after('gender'); // Single, Married, etc.
            
            // Medical History (Self-Reported)
            $table->text('allergies')->nullable();
            $table->text('current_medication')->nullable();
            $table->text('existing_medical_conditions')->nullable();
            
            // Health Programs
            $table->boolean('is_philhealth_member')->default(false);
            $table->boolean('is_senior_citizen_or_pwd')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth', 'gender', 'civil_status',
                'allergies', 'current_medication', 'existing_medical_conditions',
                'is_philhealth_member', 'is_senior_citizen_or_pwd'
            ]);
        });
    }
};