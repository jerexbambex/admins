<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammeTypeChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programme_type_change_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('changeable');
            $table->integer('old_progtype');
            $table->integer('new_progtype');
            $table->foreignId('user_id');
            $table->string('form_number');
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
        Schema::dropIfExists('programme_type_change_logs');
    }
}
