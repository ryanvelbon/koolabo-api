<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectLikesTable extends Migration
{
    public function up()
    {
        Schema::create('project_likes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('project_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('CASCADE');
            $table->foreign('project_id')
                        ->references('id')
                        ->on('projects')
                        ->onDelete('CASCADE');
                        
            $table->unique(array('user_id', 'project_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_likes');
    }
}
