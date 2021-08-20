<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Reference Tables: Migrations
|--------------------------------------------------------------------------
|
| Here is where you can you can define migrations to any reference tables
|
*/

class CreateReferenceTables extends Migration
{
    public function up()
    {
        Schema::create('_project_types', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
        });

        Schema::create('_project_stages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
        });

        Schema::create('_project_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
        });

        Schema::create('_project_timelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('min_n_days');
            $table->unsignedSmallInteger('max_n_days');
            $table->string('title', 40);
        });
    }

    public function down()
    {
        Schema::dropIfExists('_project_types');
        Schema::dropIfExists('_project_stages');
        Schema::dropIfExists('_project_statuses');
        Schema::dropIfExists('_project_timelines');
    }
}
