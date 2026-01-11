<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_histories', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_name'); // Stored as string to preserve log if medicine is deleted
            $table->string('action_type'); // Added, Edited, Deleted, Released
            $table->integer('quantity_changed')->nullable(); // e.g., +10, -5
            $table->text('description')->nullable();
            $table->timestamp('performed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_histories');
    }
};