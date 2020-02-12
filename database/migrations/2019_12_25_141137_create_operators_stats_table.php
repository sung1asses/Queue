<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatorsStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operators_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('queue_list_id')->index();

            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operators_stats');
    }
}
