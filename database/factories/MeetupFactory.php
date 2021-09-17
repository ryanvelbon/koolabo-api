<?php

namespace Database\Factories;

use App\Models\Meetup;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

class MeetupFactory extends Factory
{
    protected $model = Meetup::class;

    public function definition()
    {
        $startTime = now()->roundHour()->addDay(rand(1,14))->addHour(rand(1,24))->addMinute(rand(0,3)*15);
        return [
            'organizer_id' => User::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'address_line1' => $this->faker->address(),
            'start_time' => $startTime,
            'end_time' =>$startTime->addMinute(rand(3,10)*15)
        ];
    }
}
