<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

use Tests\TestCase;
use App\Models\User;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_create_project()
    {
        $this->seed();

        $user = User::factory()->create();

        $data = [
            'title' => "History of Flamenco Documentary",
            'description' => $this->faker()->paragraph(),
            'type' => DB::table('_project_types')
                        ->inRandomOrder()->first()->id,
            'projected_timeline' => DB::table('_project_timelines')
                        ->inRandomOrder()->first()->id,
            'planned_start_date' => "2022-01-01",
            'planned_end_date' => "2022-04-01"
        ];

        $response = $this->actingAs($user, 'api')
                         ->json('POST', '/api/projects', $data);

        $response->assertStatus(201);
    }
}
