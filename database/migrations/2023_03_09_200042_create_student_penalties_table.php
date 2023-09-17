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
        Schema::create('student_penalties', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('log_id');
            $table->string('std_no', 30)->nullable();
            $table->string('session');
            $table->string('semester');
            $table->integer('level_id');
            $table->integer('for_semesters');
            $table->enum('penalty', ['suspend', 'expel', 'sick'])->default('suspend');
            $table->string('description')->nullable();
            $table->foreignId('user_id');
            $table->date('date_penalized')->nullable();
            $table->timestamps();
            $table->string('reinstated_to')->nullable();
            $table->foreignId('reinstated_by')->nullable();
            $table->timestamp('reinstated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_penalties');
    }
};
