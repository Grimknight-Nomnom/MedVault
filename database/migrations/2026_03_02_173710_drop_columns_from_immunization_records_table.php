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
        Schema::table('immunization_records', function (Blueprint $table) {
            $table->dropColumn([
                'bcg_date',
                'hepb_birth_date',
                'penta_1_date',
                'penta_2_date',
                'penta_3_date',
                'opv_1_date',
                'opv_2_date',
                'opv_3_date',
                'ipv_1_date',
                'pcv_1_date',
                'pcv_2_date',
                'pcv_3_date',
                'mmr_1_date',
                'mmr_2_date',
                'additional_notes',
                'visit_date',
                'visit_age',
                'visit_temp',
                'visit_weight',
                'visit_length',
                'visit_hc',
                'visit_cc',
                'visit_ac',
                'type_of_bakuna',
                'doctor_instructions',
                'next_visit'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('immunization_records', function (Blueprint $table) {
            // Re-adding the columns if the migration is rolled back
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

            $table->date('visit_date')->nullable();
            $table->string('visit_age')->nullable();
            $table->string('visit_temp')->nullable();
            $table->string('visit_weight')->nullable();
            $table->string('visit_length')->nullable();
            $table->string('visit_hc')->nullable();
            $table->string('visit_cc')->nullable();
            $table->string('visit_ac')->nullable();
            $table->string('type_of_bakuna')->nullable();
            $table->text('doctor_instructions')->nullable();
            $table->date('next_visit')->nullable();
        });
    }
};