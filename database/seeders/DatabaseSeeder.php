<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $time_start = microtime(true);

        $this->call([

            ReferenceTablesSeeder::class,

            LanguagesTableSeeder::class,
            CountriesTableSeeder::class,
            CitiesTableSeeder::class,
            
            CraftsTableSeeder::class,
            SkillsTableSeeder::class,
            TopicsTableSeeder::class,

            UsersTableSeeder::class,
            UserLanguagesTableSeeder::class,
            UserSkillsTableSeeder::class,
            TopicUserTableSeeder::class,

            ProjectsTableSeeder::class,
            JobsTableSeeder::class,
            JobVacanciesTableSeeder::class,
            
            // JobVacancySkillTableSeeder::class,
        ]);

        $time_end = microtime(true);

        $execution_time = ($time_end - $time_start);
        $t = number_format($execution_time, 2, '.', '');

        printf("Success: Database Seeded in {$t}s");
    }
}
