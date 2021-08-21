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
        /*
         * Lorem.php helper class is not necessary
         * Just use faker.
         */
        $lorem = new Lorem();
        $createdAt = date("Y-m-d H:i:s", rand(strtotime("-6 month"), strtotime("-1 min")));
        $endsAt = date('Y-m-d', strtotime("+30 day", strtotime($createdAt)));

        return [
            // implement and use Lorem->char() function instead
            'title' => substr($lorem->words(5),0,150),
            'description' => substr($lorem->paragraph(),0,2000), // 2000 char limit
            'posted_by' => \App\Models\User::inRandomOrder()->first()->id,
            'job_id' => \App\Models\Job::inRandomOrder()->first()->id,
            'city_id' => \App\Models\City::inRandomOrder()->first()->id,
            'is_active' => rand(0,10) >= 2,
            'created_at' => $createdAt,
            'ends_at' => $endsAt,
            'deleted_at' => null,
        ];
    }
}
