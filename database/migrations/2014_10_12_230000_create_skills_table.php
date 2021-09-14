<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsTable extends Migration
{
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('status', [
                            'official',
                            'approved',
                            'proposed',
                            'rejected',
                            'blocked'])->default('proposed');
            $table->timestamps();

            $table->foreign('created_by')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('skills');
    }
}
