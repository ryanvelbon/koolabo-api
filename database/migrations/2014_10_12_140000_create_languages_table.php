<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('title', 100)->unique();
            $table->string('title_native', 100)->unique();
            $table->unsignedSmallInteger('ranking')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
