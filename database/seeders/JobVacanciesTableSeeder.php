<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\JobVacancy;
use App\Models\Job;
use App\Models\Skill;

class JobVacanciesTableSeeder extends Seeder
{
    /*
     * Creates a job listing for some jobs.
     * Technically there can be more than one listing for the same job.
     */
    public function run()
    {
        DB::table('job_vacancies')->delete();

        $jobs = Job::all();

        foreach($jobs as $job) {
            if(rand(0,10)>7) {
                JobVacancy::factory()->create(['job_id' => $job->id]);
            }
        }

        $vacancies = JobVacancy::all();
        foreach ($vacancies as $vacancy) {
            $skills = Skill::inRandomOrder()->take(rand(2,8))->get();
            $vacancy->skills()->attach($skills);
        }
    }
}
