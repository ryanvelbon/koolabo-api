<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use App\Models\Skill;
use App\Models\User;


class UserSkillsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $levels = Config::get('constants.skillLevels');

        foreach ($users as $user) {

            $skills = Skill::inRandomOrder()->limit(rand(2,8))->get();
            
            foreach($skills as $skill){
                DB::table('user_skills')->insert([
                    'user_id' => $user->id,
                    'skill' => $skill->title,
                    'level' => $levels[array_rand($levels)],
                    'uuid' => md5(rand(0,999999999999999999))
                ]);
            }
        }
    }
}