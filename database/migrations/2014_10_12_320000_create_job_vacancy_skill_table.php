<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobVacancySkillTable extends Migration
{
    public function up()
    {
        Schema::create('job_vacancy_skill', function (Blueprint $table) {
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
    }

    public function down()
    {
        Schema::dropIfExists('job_vacancy_skill');
    }
}
