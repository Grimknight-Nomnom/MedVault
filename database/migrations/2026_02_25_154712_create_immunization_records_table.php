<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('immunization_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->date('bcg_date')->nullable();
            $table->date('hepb_birth_date')->nullable();
            
            $table->date('penta_1_date')->nullable();
            $table->date('penta_2_date')->nullable();
            $table->date('penta_3_date')->nullable();
            
            $table->date('opv_1_date')->nullable();
            $table->date('opv_2_date')->nullable();
            $table->date('opv_3_date')->nullable();
            
            $table->date('ipv_1_date')->nullable();
            
            $table->date('pcv_1_date')->nullable();
            $table->date('pcv_2_date')->nullable();
            $table->date('pcv_3_date')->nullable();
            
            $table->date('mmr_1_date')->nullable();
            $table->date('mmr_2_date')->nullable();
            
            $table->text('additional_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immunization_records');
    }
};