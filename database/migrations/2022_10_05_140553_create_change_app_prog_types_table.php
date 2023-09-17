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
        Schema::create('change_app_prog_types', function (Blueprint $table) {
            $table->id();
            $table->integer('applicant_id')->nullable();
            $table->integer('initial_prog_type')->nullable();
            $table->integer('new_prog_type')->nullable();
            $table->string('initial_appno')->nullable();
            $table->string('new_appno')->nullable();
            $table->enum('status', ['changed', 'pending'])->default('pending')->nullable();
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
        Schema::dropIfExists('change_app_prog_types');
    }
};
