<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Listing;


class ListingsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('listings')->delete();

        Listing::factory()->count(80)->create();
    }
}
