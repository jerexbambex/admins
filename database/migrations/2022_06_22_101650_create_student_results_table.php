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
        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('log_id')->nullable();
            $table->string('course_code', 10)->nullable();
            $table->string('session', 10)->nullable();
            $table->string('matric_number')->nullable();
            $table->float('c_a', 3, 2)->nullable()->default(0.00);
            $table->float('mid_semester', 3, 2)->nullable()->default(0.00);
            $table->float('examination', 3, 2)->nullable()->default(0.00);
            $table->float('total', 3, 2, )->nullable()->default(0.00);
            $table->tinyText('grade')->nullable();
            $table->tinyInteger('semester')->nullable();
            $table->tinyInteger('lecturer_editable')->default(0);
            $table->tinyInteger('hod_editable')->default(1);
            $table->Integer('course_id')->nullable();
            $table->tinyInteger('prog_type_id')->nullable();
            $table->tinyInteger('level_id')->nullable();
            $table->foreignId('lecturer_id');
            $table->foreignId('lecturer_course_id');
            $table->foreignId('hod_id')->nullable();
            $table->foreignId('dean_id')->nullable();
            $table->foreignId('rector_id')->nullable();
            $table->integer('presentation')->default(0)->nullable();
            $table->enum('bos_approved', ['0', '1'])->default(0);
            $table->string('bos_number', 50);
            $table->timestamp('date_approved')->nullable();
            $table->enum('status', ['active', 'em', 'abs'])->default('active');
            $table->softDeletes();
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
        Schema::dropIfExists('student_results');
    }
};
