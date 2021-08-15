<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicUserTable extends Migration
{
    public function up()
    {
        Schema::create('topic_user', function (Blueprint $table) {
            $table->bigInteger('topic_id')->unsigned();
            $table->foreign('topic_id')
                  ->references('id')
                  ->on('topics')
                  ->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unique(array('topic_id', 'user_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('topic_user');
    }
}
