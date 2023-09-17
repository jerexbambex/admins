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
        Schema::create('student_sessions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('log_id')->nullable();
            $table->string('form_number')->nullable();
            $table->string('matric_number')->nullable();
            $table->string('session')->nullable();
            $table->tinyInteger('semester')->nullable();
            $table->string('admission_year', 5)->nullable();
            $table->tinyInteger('level_id')->default(0)->nullable();
            $table->tinyInteger('prog_id')->default(0)->nullable();
            $table->tinyInteger('prog_type_id')->default(0)->nullable();
            $table->tinyInteger('course_form')->default(0)->nullable();
            $table->tinyInteger('payment')->default(0)->nullable();
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
        Schema::dropIfExists('student_sessions');
    }
};

