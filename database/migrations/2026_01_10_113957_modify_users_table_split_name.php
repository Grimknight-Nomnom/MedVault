<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Rename 'name' to 'first_name'
            $table->renameColumn('name', 'first_name');
        });

        // 2. Add 'last_name' in a separate block to ensure 'first_name' exists
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->after('first_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->renameColumn('first_name', 'name');
        });
    }
};