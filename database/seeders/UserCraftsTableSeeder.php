<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Craft;

class UserCraftsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach($users as $user) {
            $crafts = Craft::inRandomOrder()->take(rand(1,3))->get();
            foreach($crafts as $craft) {
                $user->crafts()->attach($craft->id);
            }
        }
    }
}
