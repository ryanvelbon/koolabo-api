<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\ChatParticipant;

class CreateConversationsTables extends Migration
{
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            // $table->dateTime('last_msg_id'); // not possible because `messages` table is created after
            $table->tinyInteger('type');
            // $table->tinyInteger('status'); // in the case of private chats, chats can be requested, accepted and terminated
            $table->timestamps();

            // additional columns for group chats
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('title', 40)->nullable();
            $table->string('description', 800)->nullable();
            $table->string('thumbnail')->nullable();
            $table->foreignId('language_id')->nullable();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::create('chat_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id');
            $table->foreignId('user_id');
            $table->tinyInteger('role')->default(ChatParticipant::ROLE_USER);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['chat_id', 'user_id']);
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('chat_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('body');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chat_participants');
        // Schema::dropIfExists('group_chat_invites');
        Schema::dropIfExists('chats');
    }
}
