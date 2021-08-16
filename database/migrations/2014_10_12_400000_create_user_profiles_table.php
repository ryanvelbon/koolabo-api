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
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->date('date_of_birth')->nullable();

            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->foreign('location_id')->references('id')->on('locations');

            $table->string('profile_pic')->default('user.jpg');

            $table->string('bio', 2000)->nullable();

            $table->boolean('complete')->default(false);

            // If you make any changes to this schema, update the Controller's profileComplete() function
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
