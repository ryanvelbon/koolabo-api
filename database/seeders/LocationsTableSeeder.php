<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LocationsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('locations')->delete();

        $locations = \App\Models\Location::factory()->count(10)->create();
    }
}
