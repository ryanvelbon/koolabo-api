<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendshipsTables extends Migration
{
    public function up()
    {
        Schema::create('followings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('follower_id');
            $table->unsignedBigInteger('followee_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('follower_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('followee_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->unique(['follower_id', 'followee_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('followings');
    }
}
