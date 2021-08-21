<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Project;

class ProjectsTableSeeder extends Seeder
{
    public function run()
    {
		DB::table('projects')->delete();

		Project::factory()->count(5)->create();
    }
}
