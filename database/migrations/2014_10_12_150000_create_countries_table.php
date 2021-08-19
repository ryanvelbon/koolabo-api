<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->char('iso', 2);
            $table->string('name', 80);
            $table->string('nicename', 80);
            $table->char('iso3', 3)->nullable();
            $table->unsignedSmallInteger('numcode')->nullable();
            $table->integer('phonecode');
        });
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
