<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopicUserTableSeeder extends Seeder
{
    public function run()
    {
        $topics = \App\Models\Topic::orderBy('id')->get();
        $users = \App\Models\User::orderBy('id')->get();

        $entries = random_id_pairs($users, $topics);

        foreach($entries as $entry){
        	DB::table('topic_user')->insert(
        		['user_id' => $entry[0], 'topic_id' => $entry[1]]
        	);
        }        
    }
}