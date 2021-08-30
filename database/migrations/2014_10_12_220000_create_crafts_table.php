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
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_root_category')->default(False);

            // $table->foreign('parent_id')->references('id')->on('crafts');
        });
    }

    public function down()
    {
        Schema::dropIfExists('crafts');
    }
}
