<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('craft_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();

            // foreign keys
            $table->foreign('craft_id')->references('id')->on('crafts')->onDelete('CASCADE');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
