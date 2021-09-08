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
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
