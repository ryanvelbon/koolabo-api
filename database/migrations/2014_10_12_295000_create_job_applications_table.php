<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsTable extends Migration
{
    public function up()
    {
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
    }
}
