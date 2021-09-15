<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTopicsTable extends Migration
{
    public function up()
    {
        Schema::create('user_topics', function (Blueprint $table) {
            $table->bigInteger('topic_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->unique(array('topic_id', 'user_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_topics');
    }
}
