<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingSkillTable extends Migration
{
    public function up()
    {
        Schema::create('listing_skill', function (Blueprint $table) {
            $table->bigInteger('listing_id')->unsigned();
            $table->foreign('listing_id')
                  ->references('id')
                  ->on('listings')
                  ->onDelete('cascade');

            $table->bigInteger('skill_id')->unsigned();
            $table->foreign('skill_id')
                  ->references('id')
                  ->on('skills')
                  ->onDelete('cascade');

            $table->unique(array('listing_id', 'skill_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('listing_skill');
    }
}
