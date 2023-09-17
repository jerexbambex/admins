<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBosLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bos_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('option_id');
            $table->bigInteger('adm_year');
            $table->string('session', 25);
            $table->integer('level_id');
            $table->integer('semester_id');
            $table->bigInteger('prog_id');
            $table->bigInteger('prog_type_id');
            $table->string('bos_number', 50);
            $table->integer('presentation')->default(0);
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
        Schema::dropIfExists('bos_logs');
    }
}
