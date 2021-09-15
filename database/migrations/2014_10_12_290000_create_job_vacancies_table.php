<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobVacanciesTable extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        Schema::dropIfExists('job_vacancies');
    }
}