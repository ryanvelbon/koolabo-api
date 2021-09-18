<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\Models\User;
use App\Models\Topic;

class ProjectsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('project_likes')->delete();
		DB::table('projects')->delete();

        $projects = Project::factory()->count(100)->create();

        $maxLikes = User::count()<100 ? User::count() : 100;

        // add likes, followers and topics
        foreach($projects as $project) {

            $users = User::inRandomOrder()->take(rand(0,$maxLikes))->get();
            $project->likes()->attach($users);

            foreach ($users as $user) {
                if (rand(1,10)>7) {
                    $project->followers()->attach($user);
                }
            }

            $topics = Topic::inRandomOrder()->take(rand(2,5))->get();
            $project->topics()->attach($topics);
        }
    }
}
