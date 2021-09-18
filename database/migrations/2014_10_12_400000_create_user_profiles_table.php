<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            
            $table->id();

            $table->bigInteger('user_id')->unsigned()->nullable();

            $table->string('first_name', 30)->nullable();
            $table->string('last_name', 30)->nullable();
            $table->string('gender', 15)->nullable();
            $table->date('date_of_birth')->nullable();

            $table->bigInteger('city_id')->unsigned()->nullable();
            
            $table->string('profile_pic')->default('user.jpg');
            $table->string('banner_pic')->default('banner.jpg');

            $table->string('bio_short', 160)->nullable();
            $table->string('bio_long', 2000)->nullable();

            $table->string('availability', 30)->nullable();
            $table->boolean('complete')->default(false);

            // foreign keys
            $table->foreign('user_id')->references('id')->on('users')
                                ->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities');

            // If you make any changes to this schema, update the Controller's profileComplete() function
        });

        Schema::create('user_crafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('craft_id')->index();

            $table->unique(['user_id', 'craft_id']);
        });

        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('skill_id')->index();
            $table->string('level', 20);

            $table->unique(['user_id', 'skill_id']);
        });

        Schema::create('user_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->index();
            $table->foreignId('user_id')->index();

            $table->unique(['user_id', 'topic_id']);
        });

        Schema::create('user_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('language_id')->index();

            $table->unique(['user_id', 'language_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_languages');
        Schema::dropIfExists('user_topics');
        Schema::dropIfExists('user_skills');
        Schema::dropIfExists('user_crafts');
        Schema::dropIfExists('user_profiles');
    }
}
