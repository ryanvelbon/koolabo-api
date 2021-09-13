<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSkillsTable extends Migration
{
    public function up()
    {
        Schema::create('user_skills', function (Blueprint $table) {

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->string('skill', 60); // instead of FK, we should allow any text

            // $table->bigInteger('skill_id')->unsigned();
            // $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');

            $table->string('level', 20);
            $table->char('uuid', 32)->unique(); // md5 hash

            $table->unique(array('skill', 'user_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_skills');
    }
}

