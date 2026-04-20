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
        Schema::table('staff', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('picture_path');
            $table->text('inactive_reason')->nullable()->after('is_active');
            $table->timestamp('deactivated_at')->nullable()->after('inactive_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'inactive_reason', 'deactivated_at']);
        });
    }
};