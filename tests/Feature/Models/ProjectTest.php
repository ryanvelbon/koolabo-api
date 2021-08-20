<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;
use App\Models\User;

class ProjectTest extends TestCase
{
    public function user_can_create_project()
    {
        $this->seed();

        $data = [

        ];

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
                         ->json('POST', '/api/projects', $data);

        $response->assertStatus(201);
    }
}
