<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('description', 2000);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('manager'); // by default this will be the user who created the Project
            $table->unsignedBigInteger('type');
            $table->unsignedBigInteger('projected_timeline');
            $table->date('planned_start_date');
            $table->date('planned_end_date');
            $table->timestamps();


            // budget

            // funding (e.g., self-funding?)

            // language (what language will the team communicate in?)

            // status

            /* 
             * stage
             *
             * lifecycle depends on the project type
             * e.g., Producing a film involves: script development, budgeting, production, post-production, marketing, and distribution
             *
             */

            
            // relationships
            $table->foreign('created_by')->references('id')->on('users')->onDelete('RESTRICT');
            $table->foreign('manager')->references('id')->on('users')->onDelete('RESTRICT');
            $table->foreign('type')->references('id')->on('_project_types')->onDelete('RESTRICT');
            $table->foreign('projected_timeline')->references('id')->on('_project_timelines')->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}