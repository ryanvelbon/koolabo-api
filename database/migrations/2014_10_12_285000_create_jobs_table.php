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

        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('description', 2000);
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->boolean('is_active')->default(True);
            $table->date('ends_at');
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();

            // foreign key relationships
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('posted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });

        Schema::create('job_vacancy_skills', function (Blueprint $table) {
            $table->bigInteger('job_vacancy_id')->unsigned();
            $table->foreign('job_vacancy_id')
                  ->references('id')
                  ->on('job_vacancies')
                  ->onDelete('cascade');

            $table->bigInteger('skill_id')->unsigned();
            $table->foreign('skill_id')
                  ->references('id')
                  ->on('skills')
                  ->onDelete('cascade');

            $table->unique(array('job_vacancy_id', 'skill_id'));
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_vacancy_id');
            $table->unsignedBigInteger('applicant_id');
            $table->string('msg', 1000);
            $table->timestamps();

            // foreign keys
            $table->foreign('job_vacancy_id')->references('id')->on('job_vacancies')->onDelete('CASCADE');
            $table->foreign('applicant_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_vacancy_skills');
        Schema::dropIfExists('job_vacancies');
        Schema::dropIfExists('jobs');
    }
}
