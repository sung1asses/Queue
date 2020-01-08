<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueueListUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_list_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('queue_list_id')->index();
            $table->foreign('queue_list_id')->references('id')->on('queue_lists')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('queue_list_user', function (Blueprint $table) {
            $table->dropForeign(['queue_list_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('queue_list_user');
    }
}
