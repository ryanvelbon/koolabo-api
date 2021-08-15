<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillUserTable extends Migration
{
    public function up()
    {
        Schema::create('skill_user', function (Blueprint $table) {
            $table->bigInteger('skill_id')->unsigned();
            $table->foreign('skill_id')
                  ->references('id')
                  ->on('skills')
                  ->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unique(array('skill_id', 'user_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('skill_user');
    }
}

