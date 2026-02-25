<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('immunization_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // <--- This is the missing column
            
            $table->date('date')->nullable();
            $table->string('age')->nullable();
            $table->string('temp')->nullable();
            $table->string('weight')->nullable();
            $table->string('length')->nullable();
            $table->string('hc')->nullable();
            $table->string('cc')->nullable();
            $table->string('ac')->nullable();
            $table->string('type_of_bakuna')->nullable();
            $table->text('doctor_instructions')->nullable();
            $table->date('next_visit')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immunization_logs');
    }
};