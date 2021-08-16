<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Helpers\Lorem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Listing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $lorem = new Lorem();

        return [
            // implement and use Lorem->char() function instead
            'title' => substr($lorem->words(5),150),
            'description' => substr($lorem->paragraph(),0,2000), // 2000 char limit
            'paid' => rand(0,1) == 1,
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'craft_id' => \App\Models\Craft::inRandomOrder()->first()->id,
            'location_id' => \App\Models\Location::inRandomOrder()->first()->id,
        ];
    }
}
