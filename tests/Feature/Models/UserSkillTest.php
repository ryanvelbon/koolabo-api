<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;
use App\Models\User;
use App\Models\Skill;

class UserSkillTest extends TestCase
{
    use RefreshDatabase;
    // use Faker;

    private function random_level()
    {
        $levels = Config::get('constants.skillLevels');
        return $levels[array_rand($levels)];
    }

    public function test_retrieve_user_skills()
    {
        $this->seed();

        $user = User::inRandomOrder()->first();

        $response = $this->json('GET', "/api/users/{$user->id}/skills");
        $response->assertStatus(200);

        $nSkillsInUserObject = $user->skills->count();
        $nSkillsInResponse = sizeof(json_decode($response->getContent()));

        $this->assertEquals($nSkillsInUserObject, $nSkillsInResponse);
    }

    // standard skills
    public function test_user_can_add_listed_skill_to_profile()
    {
        $this->seed();

        // add a listed skill to database
        $skill = Skill::create(['title' => 'foobar']);
        $nSkills_preRequest_db = Skill::count();

        $user = User::inRandomOrder()->first();
        $nSkills_preRequest_user = $user->skills->count();
        

        $data = [
            'skill' => 'foobar',
            'level' => $this->random_level()
        ];

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('POST', "/api/users/me/skills", $data);
        $response->assertStatus(201);
        $this->assertEquals($user->refresh()->skills->count(), $nSkills_preRequest_user+1);
        $this->assertEquals(Skill::count(), $nSkills_preRequest_db);
    }

    // custom skills
    public function test_user_can_add_unlisted_skill_to_profile()
    {
        $this->seed();
        $nSkills_preRequest_db = Skill::count();

        $user = User::inRandomOrder()->first();
        $nSkills_preRequest_user = $user->skills->count();

        $data = [
            'skill' => 'zoobar',
            'level' => $this->random_level()
        ];

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('POST', "/api/users/me/skills", $data);
        $response->assertStatus(201);
        $this->assertEquals($user->refresh()->skills->count(), $nSkills_preRequest_user+1);
        $this->assertEquals(Skill::count(), $nSkills_preRequest_db+1);
    }

    public function test_user_can_edit_their_skill_level()
    {
        $this->seed();
        // new user is created; initially has 0 skills
        $user = User::factory()->create();
        $this->assertEquals($user->skills->count(), 0);
        // user is assigned a skill (but not via API) and now the user has 1 skill
        $skill = Skill::inRandomOrder()->first();
        $user->skills()->attach($skill->id, ['level' => 'beginner']);
        $user->refresh();
        $this->assertEquals($user->skills->count(), 1);
        $this->assertEquals($user->skills->find($skill->id)->pivot->level, 'beginner');
        // user updates `level` attribute
        $data = ['level' => 'advanced'];
        Sanctum::actingAs($user, ['*']);
        $response = $this->json('PATCH', "/api/users/me/skills/{$skill->id}", $data);
        $response->assertStatus(200);
        // user should still only have 1 skill but the `level` should have been updated
        $user->refresh();
        $this->assertEquals($user->skills->count(), 1);
        $this->assertEquals($user->skills->find($skill->id)->pivot->level, 'advanced');
    }

    public function test_user_can_remove_skill()
    {
        $this->seed();
        $nSkills_preRequest_db = Skill::count();

        $user = User::inRandomOrder()->first();
        $nSkills_preRequest_user = $user->skills->count();

        $skill = $user->skills()->inRandomOrder()->first();

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('DELETE', "/api/users/me/skills/{$skill->id}");
        $response->assertStatus(200);
        $user->refresh();
        $this->assertNull($user->skills->find($skill->id));
        $this->assertEquals($user->skills->count(), $nSkills_preRequest_user-1);
        $this->assertEquals(Skill::count(), $nSkills_preRequest_db);

    }

    public function test_404_response_if_users_try_to_remove_skills_they_do_not_have()
    {
        $this->seed();
        $user = User::inRandomOrder()->first();
        $skill = Skill::create(['title' => 'goobar']);
        Sanctum::actingAs($user, ['*']);
        $response = $this->json('DELETE', "/api/users/me/skills/{$skill->id}");
        $response->assertStatus(404);
    }

    public function test_404_response_if_users_try_to_edit_skills_they_do_not_have()
    {
        $this->seed();
        $user = User::inRandomOrder()->first();
        $skill = Skill::create(['title' => 'goobar']);
        Sanctum::actingAs($user, ['*']);
        $data = ['level' => 'advanced'];
        $response = $this->json('PATCH', "/api/users/me/skills/{$skill->id}", $data);
        $response->assertStatus(404);
    }

    public function FAILS_add_skill_is_idempotent()
    {
        $this->seed();
        $user = User::factory()->create();
        $skill = Skill::inRandomOrder()->first();
        // user sends a POST request with the same data twice
        Sanctum::actingAs($user, ['*']);
        $data = ['skill' => $skill->title, 'level' => 'beginner'];
        $response = $this->json('POST', '/api/users/me/skills', $data);
        $response->assertStatus(201);
        $response = $this->json('POST', '/api/users/me/skills', $data);
        $response->assertStatus(200);
        // user should only have 1 skill
        $this->assertEquals($user->refresh()->skills->count(), 1);
    }
}
