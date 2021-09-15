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

            /*
             *  Users can now create entries fo the `crafts`, `skills`
             *  and `topics` tables.
             *
             *  For example, when a user adds a skill that is not
             *  in the database, the Controller will create an entry
             *  in the `skills` table with a 'created_by' FK field
             *  which points to that user. Hence we must seed the
             *  `users` table before these tables.
             */

            UsersTableSeeder::class,
            
            CraftsTableSeeder::class,
            SkillsTableSeeder::class,
            TopicsTableSeeder::class,

            
            UserLanguagesTableSeeder::class,
            UserCraftsTableSeeder::class,
            UserSkillsTableSeeder::class,
            UserTopicsTableSeeder::class,

            ProjectsTableSeeder::class,
            ProjectLikesTableSeeder::class,

            JobsTableSeeder::class,
            JobVacanciesTableSeeder::class,
            
            // JobVacancySkillTableSeeder::class,
        ]);

        $time_end = microtime(true);

        $execution_time = ($time_end - $time_start);
        $t = number_format($execution_time, 2, '.', '');

        if(config('database.default') == 'mysql')
            printf("Success: Database Seeded in {$t}s");
    }
}
