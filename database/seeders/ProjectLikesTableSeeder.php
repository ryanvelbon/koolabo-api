<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Project;

class ProjectLikesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('project_likes')->delete();

        $projects = Project::all();

        $maxLikes = User::count()<100 ? User::count() : 100;

        foreach($projects as $project) {
            $users = User::inRandomOrder()->take(rand(0,$maxLikes))->get();
            foreach($users as $user)
                $project->likers()->attach($user->id);
        }
    }
}
