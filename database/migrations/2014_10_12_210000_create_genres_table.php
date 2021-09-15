<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenresTable extends Migration
{
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_root_category')->default(False);

            $table->unique(array('title', 'parent_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('genres');
    }
}
