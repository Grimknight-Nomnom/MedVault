<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medicine_histories', function (Blueprint $table) {
            // Adding indexes to columns used frequently in dashboard reporting
            // action_type: 'Released', 'Expired', 'Added', 'Edited'
            // created_at: Used for monthly/yearly filtering
            $table->index(['action_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicine_histories', function (Blueprint $table) {
            $table->dropIndex(['action_type', 'created_at']);
        });
    }
};