<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_results_configs', function (Blueprint $table) {
            $table->id();
            $table->string('session_year', 5)->nullable();
            $table->bigInteger('semester_id');
            $table->timestamp('student_registration_start_date')->nullable();
            $table->timestamp('student_registration_end_date')->nullable();
            $table->timestamp('lecturer_upload_start_date')->nullable();
            $table->timestamp('lecturer_upload_end_date')->nullable();
            $table->timestamp('departmental_moderation_start_date')->nullable();
            $table->timestamp('departmental_moderation_end_date')->nullable();
            $table->timestamp('bos_moderation_start_date')->nullable();
            $table->timestamp('bos_moderation_end_date')->nullable();
            $table->timestamp('tuition_payments_start_date')->nullable();
            $table->timestamp('tuition_payments_end_date')->nullable();
            $table->timestamp('course_update_fee_start_date')->nullable();
            $table->timestamp('course_update_fee_end_date')->nullable();
            $table->timestamp('student_results_enable_start_date')->nullable();
            $table->timestamp('student_results_enable_end_date')->nullable();
            $table->enum('for_cec', ['0','1','2'])->default(0)->comment('0 - not cec, 1 - for cec, 2 - all');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_results_configs');
    }
};
