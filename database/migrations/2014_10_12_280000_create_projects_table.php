<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /*

    Additional columns we could incorporate into table:
    - budget
    - funding (e.g., self-funding?)
    - language (what language will the team communicate in?)
    - status
    - stage
        lifecycle depends on the project type
        e.g., Producing a film involves: script development, budgeting, production, post-production, marketing, and distribution
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('description', 2000);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('manager_id'); // by default this will be the user who created the Project
            $table->unsignedBigInteger('type');
            $table->unsignedBigInteger('projected_timeline');
            $table->date('planned_start_date');
            $table->date('planned_end_date');
            $table->timestamps();
            $table->softDeletes();
            
            // relationships
            $table->foreign('created_by')->references('id')->on('users')->onDelete('RESTRICT');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->foreign('type')->references('id')->on('_project_types')->onDelete('RESTRICT');
            $table->foreign('projected_timeline')->references('id')->on('_project_timelines')->onDelete('RESTRICT');
        });

        Schema::create('project_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->foreignId('topic_id')->index();
            $table->timestamps();

            $table->unique(['project_id', 'topic_id']);
        });

        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('project_id')->index();
            $table->timestamps();

            $table->unique(['user_id', 'project_id']);
        });

        Schema::create('project_invites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('project_id');
            $table->string('msg');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');

            // *REVISE*
            $table->unique(['recipient_id', 'project_id', 'status']);
        });

        Schema::create('project_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('project_id')->index();
            $table->timestamps();

            $table->unique(['user_id', 'project_id']);
        });

        Schema::create('project_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('project_id')->index();
            $table->timestamps();

            $table->unique(['user_id', 'project_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_followers');
        Schema::dropIfExists('project_likes');
        Schema::dropIfExists('project_invites');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('project_topics');
        Schema::dropIfExists('projects');
    }
}