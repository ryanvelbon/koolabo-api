<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListingSkillTableSeeder extends Seeder
{
    public function run()
    {
        $listings = \App\Models\Listing::orderBy('id')->get();
        $skills = \App\Models\Skill::orderBy('id')->get();

        $entries = random_id_pairs($listings, $skills);

        foreach($entries as $entry){
        	DB::table('listing_skill')->insert(
        		['listing_id' => $entry[0], 'skill_id' => $entry[1]]
        	);
        }        
    }
}