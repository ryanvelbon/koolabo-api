<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cities')->delete();

        $file = fopen(dirname(__DIR__, 1) . "/csv/cities.csv", "r");

        if ($file) {
            while (($line = fgets($file)) !== false) {
                $values = explode(',', $line);
                DB::table('cities')->insert([
                    'slug' => $values[0],
                    'city' => $values[1],
                    'state' => $values[2],
                    'country' => $values[3],
                ]);
            }

            fclose($file);
        } else {
            // error opening the file.
        }
    }
}
