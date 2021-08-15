<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCraftsTable extends Migration
{
    public function up()
    {
        Schema::create('crafts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crafts');
    }
}
