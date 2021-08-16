<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ListingsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('listings')->delete();

        $listings = \App\Models\Listing::factory()->count(80)->create();
    }
}
