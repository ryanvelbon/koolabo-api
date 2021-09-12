<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    public function run()
    {
      $file = fopen(database_path().'/csv/languages.csv', 'r');

      fgets($file); // read first line so that it is excluded from the while loop since this line contains the column names rather than a data entry

      while (($line = fgetcsv($file, null, ';')) !== FALSE) {
        //$line is an array of the csv elements
        DB::table('languages')->insert([
          'code' => $line[1],
          'title' => $line[2],
          'title_native' => $line[3],
          'ranking' => $line[4],
        ]);
      }
      fclose($file);
    }
}
