<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description', 500);
            $table->boolean('paid');

            // foreign keys
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('craft_id')->unsigned();
            $table->bigInteger('location_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('craft_id')->references('id')->on('crafts');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('listings');
    }
}
