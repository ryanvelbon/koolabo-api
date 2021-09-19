<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class FollowingsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            $followers = User::where('id', '!=', $user->id)->inRandomOrder()->take(rand(0,20))->get();
            $user->followers()->attach($followers);
        }
    }
}
