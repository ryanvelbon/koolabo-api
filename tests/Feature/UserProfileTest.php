<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Language;
use App\Models\Craft;
use App\Models\Skill;
use App\Models\Topic;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_eloquent_relationships()
    {
        $this->seed();

        $a = 3;
        $b = 2;
        $c = 5;
        $d = 10;

        $user = User::factory()->create();

        $languages = Language::inRandomOrder()->take($a)->get();
        $user->languages()->attach($languages);

        $crafts = Craft::inRandomOrder()->take($b)->get();
        $user->crafts()->attach($crafts);

        $skills = Skill::inRandomOrder()->take($c)->get();
        foreach ($skills as $skill) {
            $user->skills()->attach($skill, ['level' => 'beginner']);
        }

        $topics = Topic::inRandomOrder()->take($d)->get();
        $user->topics()->attach($topics);

        $user->refresh();

        $this->assertEquals($a, $user->languages->count());
        $this->assertEquals($b, $user->crafts->count());
        $this->assertEquals($c, $user->skills->count());
        $this->assertEquals($d, $user->topics->count());

    }
}
