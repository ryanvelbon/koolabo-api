<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\JobVacancy;


class JobVacanciesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('job_vacancies')->delete();

        JobVacancy::factory()->count(80)->create();
    }
}
