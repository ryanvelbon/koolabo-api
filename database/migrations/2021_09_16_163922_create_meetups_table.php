<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetupsTable extends Migration
{
    public function up()
    {
        Schema::create('meetups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->index();
            $table->string('title');
            $table->text('description');
            $table->decimal('lat', 11, 8);
            $table->decimal('lng', 11, 8);
            $table->text('address_line1');
            $table->text('address_line2')->nullable();
            $table->timestampTz('start_time', $precision = 0);
            $table->timestampTz('end_time', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meetups');
    }
}
