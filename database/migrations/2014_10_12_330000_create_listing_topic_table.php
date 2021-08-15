<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingTopicTable extends Migration
{
    public function up()
    {
        Schema::create('listing_topic', function (Blueprint $table) {
            $table->bigInteger('listing_id')->unsigned();
            $table->foreign('listing_id')
                  ->references('id')
                  ->on('listings')
                  ->onDelete('cascade');

            $table->bigInteger('topic_id')->unsigned();
            $table->foreign('topic_id')
                  ->references('id')
                  ->on('topics')
                  ->onDelete('cascade');

            $table->unique(array('listing_id', 'topic_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('listing_topic');
    }
}
