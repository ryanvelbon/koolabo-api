<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Meetup;
use App\Models\User;

class MeetupsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('meetups')->delete();

        $meetups = Meetup::factory(10)->create();

        foreach($meetups as $meetup) {

            for ($i=0; $i<rand(1,5); $i++) { 
                $meetup->images()->create(['path' => 'image.png']);
            }

            $users = User::inRandomOrder()->take(rand(3,50))->get();

            foreach ($users as $user) {
                $meetup->rsvps()->attach($user, ['status' => rand(1,5)]);
            }
            
        }
    }
}
