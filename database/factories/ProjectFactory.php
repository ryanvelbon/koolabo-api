<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\Models\User;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $timeline = DB::table('_project_timelines')->inRandomOrder()->first();
        $rand_n_days = rand($timeline->min_n_days, $timeline->max_n_days);
        $plannedStartDate = date("Y-m-d", rand(strtotime("-1 week"), strtotime("+3 month")));
        $plannedEndDate = date('Y-m-d', strtotime("+{$rand_n_days} day", strtotime($plannedStartDate)));

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'created_by' => $user->id,
            'manager_id' => $user->id,
            'type' => DB::table('_project_types')->inRandomOrder()->first()->id,
            'projected_timeline' => DB::table('_project_timelines')->inRandomOrder()->first()->id,
            'planned_start_date' => $plannedStartDate,
            'planned_end_date' => $plannedEndDate
        ];
    }
}