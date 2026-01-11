<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // 1. Change to DATE (removes time component)
            $table->date('appointment_date')->change();
            
            // 2. Add queue_number column
            $table->integer('queue_number')->after('appointment_date');

            // 3. Add Unique Constraint (No duplicate queue numbers on same day)
            $table->unique(['appointment_date', 'queue_number']);
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique(['appointment_date', 'queue_number']);
            $table->dropColumn('queue_number');
            $table->dateTime('appointment_date')->change();
        });
    }
};