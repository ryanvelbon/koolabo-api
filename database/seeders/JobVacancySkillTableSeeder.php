<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobVacancySkillTableSeeder extends Seeder
{
    public function run()
    {
        $listings = \App\Models\JobVacancy::orderBy('id')->get();
        $skills = \App\Models\Skill::orderBy('id')->get();

        $entries = random_id_pairs($listings, $skills);

        foreach($entries as $entry){
        	DB::table('job_vacancy_skill')->insert(
        		['job_vacancy_id' => $entry[0], 'skill_id' => $entry[1]]
        	);
        }        
    }
}