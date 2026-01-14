<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('appointment_settings', function (Blueprint $table) {
        $table->id();
        $table->date('date')->unique();
        $table->integer('max_appointments')->default(30);
        $table->boolean('is_closed')->default(false); // Optional: to mark holidays easily
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_settings');
    }
};
