<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Reference Tables: Seeders
|--------------------------------------------------------------------------
|
| Here is where you can you can define seeders to any reference tables
|
*/

class ReferenceTablesSeeder extends Seeder
{
    public function run()
    {
    	$this->seed_project_types_table();
    	// $this->seed_project_stages_table();
    	// $this->seed_project_statuses_table();
        $this->seed_project_timelines_table();
    }

    private function seed_project_types_table()
    {
        DB::table('_project_types')->delete();

        $file = fopen(dirname(__DIR__, 1) . "/csv/project_types.csv", "r");

        if ($file) {
            while (($line = fgets($file)) !== false) {
                $values = explode(';', $line);
                DB::table('_project_types')->insert([
                    'title' => $values[0],
                ]);
            }

            fclose($file);
        } else {
            // error opening the file.
        }    	
    }

    private function seed_project_timelines_table()
    {
        DB::table('_project_timelines')->delete();

        $file = fopen(dirname(__DIR__, 1) . "/csv/project_timelines.csv", "r");

        if ($file) {
            while (($line = fgets($file)) !== false) {
                $values = explode(';', $line);
                DB::table('_project_timelines')->insert([
                    'min_n_days' => (int) $values[0],
                    'max_n_days' => (int) $values[1],
                    'title' => $values[2],
                ]);
            }

            fclose($file);
        } else {
            // error opening the file.
        }
    }
}