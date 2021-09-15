<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\JobVacancy;
use App\Models\User;
use App\Models\Job;
use App\Models\City;

class JobVacancyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobVacancy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $createdAt = date("Y-m-d H:i:s", rand(strtotime("-6 month"), strtotime("-1 min")));
        $endsAt = date('Y-m-d', strtotime("+30 day", strtotime($createdAt)));

        return [
            'title' => $this->faker->text($maxNbChars = 150),
            'description' => $this->faker->text($maxNbChars = 2000),
            'posted_by' => User::inRandomOrder()->first()->id,
            'job_id' => Job::inRandomOrder()->first()->id,
            'city_id' => City::where('id', '<', 20)->inRandomOrder()->first()->id,
            'is_active' => rand(0,10) >= 2,
            'created_at' => $createdAt,
            'ends_at' => $endsAt,
            'deleted_at' => null,
        ];
    }
}
