<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CraftsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('crafts')->delete();

        $file = fopen(dirname(__DIR__, 1) . "/csv/crafts.csv", "r");

        if ($file) {
          
          // skip first line (column titles)
          fgets($file);

          while(($line = fgets($file)) !== false) {

            // remove trailing whitespace
            $line = rtrim($line);        

            $values = explode(';', $line);

            // error_log($line);

            // error_log(dd($values));

            // error_log(dd($values));

            DB::table('crafts')->insert([
              'id' => ($values[0] == "") ? null : (int) $values[0],
              'title' => $values[1],
              'parent_id' => ($values[2] == "") ? null : (int) $values[2],
              'is_root_category' => (bool) $values[3]
            ]);
          }
          
          fclose($file);

        } else {
          error_log("Cannot read file...");
        }
    }
}
