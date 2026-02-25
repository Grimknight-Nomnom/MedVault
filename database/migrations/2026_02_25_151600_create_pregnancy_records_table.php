<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pregnancy_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // General
            $table->date('date_of_registration')->nullable();
            $table->string('age_group')->nullable();
            $table->string('gravida_parity')->nullable();
            $table->date('lmp')->nullable();
            $table->date('edd')->nullable();

            // Trimesters (BANC)
            $table->date('visit_1')->nullable();
            $table->date('visit_2')->nullable();
            $table->date('visit_3')->nullable();
            $table->date('visit_4')->nullable();
            $table->date('visit_5')->nullable();
            $table->date('visit_6')->nullable();
            $table->date('visit_7')->nullable();
            $table->date('visit_8')->nullable();

            // Nutritional Assessment & Immunization
            $table->decimal('bmi', 5, 2)->nullable();
            $table->string('bmi_category')->nullable();
            $table->string('nutritional_remarks')->nullable();
            $table->date('td1')->nullable();
            $table->date('td2')->nullable();
            $table->date('td3')->nullable();
            $table->date('td4')->nullable();
            $table->date('td5')->nullable();

            // Prenatal Supplementation
            $table->boolean('deworming')->default(0);
            foreach(['ifa', 'mms'] as $type) {
                foreach(['v1', 'v2', 'v3', 'v4', 'v5', 'v6'] as $v) {
                    $table->integer("{$type}_{$v}_tablets")->nullable();
                    $table->date("{$type}_{$v}_date")->nullable();
                }
                $table->boolean("{$type}_completed")->default(0);
                $table->date("{$type}_completed_date")->nullable();
            }
            // CC Supplementation (High Risk)
            foreach(['v2', 'v3', 'v4'] as $v) {
                $table->integer("cc_{$v}_tablets")->nullable();
                $table->date("cc_{$v}_date")->nullable();
            }
            $table->boolean('cc_completed')->default(0);
            $table->date('cc_completed_date')->nullable();

            // Laboratory Screenings
            $table->date('lab_syphilis_date')->nullable();
            $table->string('lab_syphilis_result')->nullable();
            $table->date('lab_hiv_date')->nullable();
            $table->string('lab_hiv_result')->nullable();
            $table->date('lab_hepb_date')->nullable();
            $table->string('lab_hepb_result')->nullable();
            $table->date('lab_cbc_date')->nullable();
            $table->string('lab_cbc_result')->nullable();
            $table->date('lab_gdm_date')->nullable();
            $table->string('lab_gdm_result')->nullable();

            // Outcome & Delivery
            $table->date('outcome_date')->nullable();
            $table->string('outcome_type')->nullable();
            $table->string('delivery_type')->nullable();
            $table->integer('birth_weight')->nullable();
            $table->string('birth_weight_category')->nullable();
            $table->string('delivery_health_facility')->nullable();
            $table->string('delivery_facility_type')->nullable();
            $table->boolean('bemonc_cemonc')->default(0);
            $table->string('delivery_non_health_facility')->nullable();
            $table->string('birth_attendant')->nullable();
            $table->string('birth_attendant_others')->nullable();
            $table->date('delivery_date_actual')->nullable();
            $table->time('delivery_time_actual')->nullable();

            // Postnatal Care (4PNC) & Supplementation
            $table->date('pnc_contact_1')->nullable();
            $table->date('pnc_contact_2')->nullable();
            $table->date('pnc_contact_3')->nullable();
            $table->date('pnc_contact_4')->nullable();
            $table->boolean('completed_4pnc')->default(0);
            
            foreach(['v1', 'v2', 'v3'] as $v) {
                $table->integer("pp_ifa_{$v}_tablets")->nullable();
                $table->date("pp_ifa_{$v}_date")->nullable();
            }
            $table->boolean('pp_ifa_completed')->default(0);
            $table->date('pp_ifa_completed_date')->nullable();
            $table->boolean('pp_vita_completed')->default(0);
            $table->date('pp_vita_completed_date')->nullable();
            
            // Completion & Remarks
            $table->string('postpartum_remarks')->nullable();
            $table->boolean('completed_8anc')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pregnancy_records');
    }
};