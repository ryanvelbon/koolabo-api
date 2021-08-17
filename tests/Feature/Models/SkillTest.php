<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;
use App\Models\User;
use App\Models\Skill;


class SkillTest extends TestCase
{
	use RefreshDatabase;

    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_users_can_add_a_skill_to_their_profile()
    {
        // Run the DatabaseSeeder...
        $this->seed();

        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        
        $this->assertAuthenticated();

        $skill_id = Skill::inRandomOrder()->first()->id;

        $this->actingAs($user)->post('/profile/skills/add', [
            'skill_id' => $skill_id,
        ]);

        $this->assertEquals("abc", "abc");
        $this->assertEquals("abc", "def");
        $this->assertEquals($user->skills->count(), 1);

        
    }
}
