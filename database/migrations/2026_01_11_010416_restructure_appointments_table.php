<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // We use Schema::table since the table exists
        Schema::table('appointments', function (Blueprint $table) {
            // 1. Ensure date type (removes time if present)
            $table->date('appointment_date')->change();
            
            // 2. Add queue_number if it doesn't exist
            if (!Schema::hasColumn('appointments', 'queue_number')) {
                $table->integer('queue_number')->after('appointment_date');
            }

            // 3. Add Unique Constraint
            // We drop it first in case it exists to avoid errors, then re-add
            // $table->dropUnique(['appointment_date', 'queue_number']); // Uncomment if re-running on existing data
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