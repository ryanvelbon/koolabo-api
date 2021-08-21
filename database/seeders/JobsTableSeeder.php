<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Project;
use App\Models\Craft;
use App\Models\Job;
use App\Models\User;

class JobsTableSeeder extends Seeder
{
    public function run()
    {
        $projects = Project::all();

        foreach ($projects as $project)
        {
        	$min_jobs = 2;
        	$max_jobs = 5;

        	$n_jobs = rand($min_jobs, $max_jobs);

        	$crafts = Craft::inRandomOrder()->limit($n_jobs)->get();

        	foreach ($crafts as $craft)
        	{
        		Job::create([
        			'craft_id' => $craft->id,
        			'project_id' => $project->id,
        			'assigned_to' => User::inRandomOrder()
        								->first()->id
        		]);
        	}
        }
    }
}
