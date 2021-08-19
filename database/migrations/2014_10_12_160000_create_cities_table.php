<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80);
            $table->string('city', 80);
            $table->string('state', 80)->nullable();
            $table->string('country'); // pending: this should be a Foreign Key
            $table->unsignedSmallInteger('priority')->nullable();
            $table->boolean('app_is_available')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
