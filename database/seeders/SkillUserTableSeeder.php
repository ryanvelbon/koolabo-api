<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SkillUserTableSeeder extends Seeder
{
    public function run()
    {
        $skills = \App\Models\Skill::orderBy('id')->get();
        $users = \App\Models\User::orderBy('id')->get();

        $entries = random_id_pairs($users, $skills);

        foreach($entries as $entry){
        	DB::table('skill_user')->insert(
        		['user_id' => $entry[0], 'skill_id' => $entry[1]]
        	);
        }        
    }
}