<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CraftsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('crafts')->delete();

        $crafts = array('Animation', 'Video Editing', 'Music Production', 'Guitar', 'Graphic Design', 'Software Developer', 'Web Developer', 'Web Design', 'Marketing', 'SEO');

        foreach($crafts as $craft){
            DB::table('crafts')->insert([
                'title' => $craft,
            ]);
        }
    }
}
