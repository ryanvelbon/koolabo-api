<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Location;
use App\Helpers\Lorem;


class UsersTableSeeder extends Seeder
{
    public function run()
    {
    	DB::table('users')->delete();

        $lorem = new Lorem();

        $users = User::factory()->count(50)->create();

        foreach($users as $user){
            UserProfile::create(
                array(
                    'user_id' => $user->id,
                    'date_of_birth' => date('Y-m-d', rand(0500000000,1000000000)),
                    'location_id' => Location::inRandomOrder()->first()->id,
                    'bio' => substr($lorem->paragraph(), 0, 2000),
                )
            );
        }
    }
}
