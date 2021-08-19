<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([

            CountriesTableSeeder::class,
            CitiesTableSeeder::class,
            
            CraftsTableSeeder::class,
            SkillsTableSeeder::class,
            TopicsTableSeeder::class,

            UsersTableSeeder::class,
            SkillUserTableSeeder::class,
            TopicUserTableSeeder::class,

            ListingsTableSeeder::class,
            // ListingSkillTableSeeder::class,
            // ListingTopicTableSeeder::class,
        ]);
    }
}
