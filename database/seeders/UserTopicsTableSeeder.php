<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Topic;


class UserTopicsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach($users as $user) {
            $topics = Topic::inRandomOrder()->take(rand(3,10))->get();
            foreach($topics as $topic) {
                $user->topics()->attach($topic->id);
            }
        }
    }
}