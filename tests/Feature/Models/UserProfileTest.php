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

class UserProfileTest extends TestCase
{
    use RefreshDatabase;
    // use Faker;

    private function random_level()
    {
        $levels = Config::get('constants.skillLevels');
        return $levels[array_rand($levels)];
    }

    public function test_user_can_add_edit_and_delete_skills()
    {
        $this->seed();

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        // initially user should have 0 skills
        $this->assertEquals($user->skills->count(), 0);

        // user adds n skills to their profile
        $n = 5;
        $skills = Skill::inRandomOrder()->take($n)->get();
        foreach ($skills as $skill) {
            $data = [
                'skill' => $skill->title,
                'level' => $this->random_level(),
            ];
            $response = $this->json('POST', "/api/profile/skills", $data);
            $response->assertStatus(201);
        }

        // user should now have n skills
        $this->assertEquals($user->skills->count(), $n);

        // user deletes one of their skills
        $uuid = $user->skills[0]->uuid;
        $response = $this->json('DELETE', "/api/profile/skills/{$uuid}");
        $response->assertStatus(204);

        // user should now have one less skill
        $this->assertEquals($user->skills->count(), $n-1);

        // user edits one of their skills
        $uuid = $user->skills[0]->uuid;
        $updatedLevel = $this->random_level();
        $data = ['level' => $updatedLevel];
        $response = $this->json('PATCH', "/api/profile/skills/{$uuid}", $data);
        $response->assertStatus(204);
        $this->assertEquals(DB::table('skill_user')->where('uuid', $uuid)->first()->level, $updatedLevel);
    }

    public function test_user_cannot_edit_or_delete_skills_of_other_users()
    {
        $this->seed();

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        // user attempts to delete someone's skill
        $uuid = User::inRandomOrder()->first()->skills[0]->uuid;
        $response = $this->json('DELETE', "/api/profile/skills/{$uuid}");
        $response->assertStatus(403);

        // user attempts to edit someone's skill
        $uuid = User::inRandomOrder()->first()->skills[0]->uuid;
        $data = ['level' => $this->random_level()];
        $response = $this->json('PATCH', "/api/profile/skills/{$uuid}", $data);
        $response->assertStatus(403);

    }   
}
