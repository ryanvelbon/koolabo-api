<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Faker;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Language;
use App\Models\Craft;
use App\Models\Skill;
use App\Models\Topic;
use App\Models\City;



class UserProfilesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('user_topics')->delete();
        DB::table('user_skills')->delete();
        DB::table('user_crafts')->delete();
        DB::table('user_languages')->delete();
        DB::table('user_profiles')->delete();

        $faker = Faker\Factory::create();
        $genders = Config::get('constants.genderOptions');
        $availabilities = Config::get('constants.availabilityOptions');
        $levels = Config::get('constants.skillLevels');

        $users = User::all();

        foreach($users as $user){
            UserProfile::create(
                array(
                    'user_id' => $user->id,
                    'date_of_birth' => date('Y-m-d', rand(0500000000,1000000000)),
                    'city_id' => City::inRandomOrder()->first()->id,
                    'bio_short' => $faker->text($maxNbChars = 160),
                    'bio_long' => $faker->text($maxNbChars = 2000),
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'gender' => $genders[array_rand($genders)],
                    'availability' => $availabilities[array_rand($availabilities)],
                )
            );

            $languages = Language::inRandomOrder()->where('ranking', '<', 100)->take(rand(0,3))->get();
            $user->languages()->attach($languages);

            $crafts = Craft::inRandomOrder()->take(rand(1,3))->get();
            $user->crafts()->attach($crafts);

            $skills = Skill::inRandomOrder()->take(rand(2,8))->get();
            foreach ($skills as $skill) {
                $user->skills()->attach($skill, ['level' => $levels[array_rand($levels)]]);
            }

            $topics = Topic::inRandomOrder()->take(rand(3,10))->get();
            $user->topics()->attach($topics);
            
        }
    }
}
