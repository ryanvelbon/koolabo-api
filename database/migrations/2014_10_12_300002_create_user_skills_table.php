<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSkillsTable extends Migration
{
    public function up()
    {
        Schema::create('user_skills', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('skill_id')->unsigned();
            $table->string('level', 20);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');

            $table->unique(array('skill_id', 'user_id'));
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_skills');
    }
}

