<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Language;
use App\Models\UserLanguage;

class UserLanguagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('user_languages')->delete();

        foreach(User::all() as $user) {
            $languages = Language::inRandomOrder()
                                ->where('ranking', '<', 100)
                                ->take(rand(0,3))
                                ->get();
            foreach($languages as $language) {
                UserLanguage::create([
                    'user_id' => $user->id,
                    'language_id' => $language->id
                ]);
            }
        }
    }
}
