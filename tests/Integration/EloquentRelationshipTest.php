<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use App\Models\Craft;
use App\Models\Skill;
use App\Models\Language;

class EloquentRelationshipTest extends TestCase
{
    use RefreshDatabase;
    // use WithFaker;

    public function test_user_model_relationships()
    {
        $this->seed();

        // creates a new user with some crafts, skills, languages
        $nTopics = 5;
        $nCrafts = 3;
        $nSkills = 10;
        $nLanguages = 2;
        $user = User::factory()->create();
        $topics = Topic::inRandomOrder()->take($nTopics)->get();
        foreach($topics as $topic)
            $user->topics()->attach($topic->id);
        $crafts = Craft::inRandomOrder()->take($nCrafts)->get();
        foreach($crafts as $craft)
            $user->crafts()->attach($craft->id);
        $skills = Skill::inRandomOrder()->take($nSkills)->get();
        foreach($skills as $skill)
            $user->skills()->attach($skill->id, ['level' => 'beginner']);
        $languages = Language::inRandomOrder()->take($nLanguages)->get();
        foreach($languages as $language)
            $user->languages()->attach($language->id);

        $this->assertEquals($nTopics, $user->topics->count());
        $this->assertEquals($nCrafts, $user->crafts->count());
        $this->assertEquals($nSkills, $user->skills->count());
        $this->assertEquals($nLanguages, $user->languages->count());

        // $user->load('crafts', 'skills', 'languages');
    }

    public function SKIPtest_inverse_relationships()
    {
        $this->seed();

        $user = User::inRandomOrder()->first();
        $craft = Craft::inRandomOrder()->first();
        $skill = Skill::inRandomOrder()->first();
        $project = Project::inRandomOrder()->first();
        $job = Job::inRandomOrder()->first();

        $this->assertNotNull($user->skills);
        $this->assertNotNull($skill->users);
        $this->assertNotNull($user->crafts);
        $this->assertNotNull($craft->users);
        $this->assertNotNull($craft->jobs);
        $this->assertNotNull($job->crafts);
    }
}
