<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker;

use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Message;
use App\Models\User;

class ChatsTableSeeder extends Seeder
{
    /*
     * Messages are generated one chat at a time.
     * They are gathered together in a collection so that once all chats
     * and their corresponding messages have been generated, the messages
     * can be sorted out from oldest to newest and stored into the database.
     * This gives us realistic dummy data to work with.
     */

    /*
     * *PENDING* what about private conversations?
     */

    public function run()
    {
        DB::table('messages')->delete();
        DB::table('chat_participants')->delete();
        DB::table('chats')->delete();

        $faker = Faker\Factory::create();

        $nGroupChats = 5;

        $allMessages = collect();

        for ($i=0; $i <$nGroupChats; $i++) { 

            $participants = User::inRandomOrder()->take(rand(2,5))->get();

            $owner = $participants->pop();

            $chat = Chat::create([
                'type' => Chat::TYPE_GROUP,
                'owner_id' => $owner->id,
                'title' => $faker->text($maxNbChars=40),
                'description' => $faker->text($maxNbChars=400),
                'thumbnail' => 'image.png',
                'language_id' => rand(1,5)
            ]);

            $chat->participants()->attach($owner, ['role' => ChatParticipant::ROLE_OWNER]);
            $chat->participants()->attach($participants);

            $chat->refresh();
            $participants = $chat->participants;

            $timestamp = now();

            $nMessages = rand(1,50); // num of messages per chat

            for ($j=0; $j<$nMessages; $j++) { 

                $msg = Message::make([
                    'chat_id' => $chat->id,
                    'user_id' => $participants->random()->id,
                    'body' => $faker->sentence($nb=rand(1,20)),
                    'created_at' => $timestamp->addSecond(-1*rand(1,100)**-3*86400*30) // deducts anywhere between a few seconds and 1 month
                ]);

                $allMessages->add($msg);
            }

        }

        $sorted = $allMessages->sortBy('created_at');

        foreach ($sorted as $msg) {
            $msg->save();
        }
    }
}
