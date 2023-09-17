<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGraduatingRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('graduating_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('admission_year', 5);
            $table->foreignId('dept_option_id');
            $table->integer('core')->default(0)->comment('total unit of require core courses');
            $table->integer('elective')->default(0)->comment('total unit of require elective courses');
            $table->integer('gs')->default(0)->comment('total unit of require general studies courses');
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
        Schema::dropIfExists('graduating_requirements');
    }
}
