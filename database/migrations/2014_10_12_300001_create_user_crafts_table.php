<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCraftsTable extends Migration
{
    public function up()
    {
        Schema::create('user_crafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('craft_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('craft_id')->references('id')->on('crafts')->onDelete('CASCADE');

            $table->unique(array('user_id', 'craft_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_crafts');
    }
}
