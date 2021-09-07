<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /*
     *   POST & PUT requests to /api/projects should not include
     *   `created_by` and `manager` fields since these fields
     *   are set by the Controller.
     */
    private function generate_dummy_form_data()
    {
        $data = Project::factory()->make()->toArray();
        unset($data['created_by']);
        unset($data['manager']);

        return $data;
    }

    public function test_unauthenticated_user_cannot_create_project()
    {
        $this->seed();

        $data = $this->generate_dummy_form_data();

        $response = $this->json('POST', '/api/projects', $data);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_project()
    {
        $this->seed();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $data = $this->generate_dummy_form_data();

        $response = $this->json('POST', '/api/projects', $data);
        
        $response->assertStatus(201);
    }

    public function test_unauthorized_user_cannot_delete_project()
    {
        $this->seed();
        
        $project = Project::inRandomOrder()->first();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->json('DELETE', '/api/projects/'.$project->id);
        $response->assertStatus(403);
    }

    public function test_project_manager_can_delete_project()
    {
        $this->seed();

        $project = Project::inRandomOrder()->first();
        $user = User::find($project->manager);

        Sanctum::actingAs($user, ['*']);

        $response = $this->json('DELETE', '/api/projects/'.$project->id);
        $response->assertStatus(200);
    }

    public function test_project_manager_can_edit_project()
    {
        $this->seed();

        $project = Project::inRandomOrder()->first();
        $manager = User::find($project->manager);

        Sanctum::actingAs($manager, ['*']);

        $data = $this->generate_dummy_form_data();

        $response = $this->json('PUT', '/api/projects/'.$project->id, $data);

        $response->assertStatus(200); // should it be 204 instead?
    }

    /*
     *   Only project manager should be able to edit project
     */
    public function test_unauthorized_user_cannot_edit_project()
    {
        $this->seed();

        $user = User::factory()->create();
        $project = Project::inRandomOrder()->first();

        Sanctum::actingAs($user, ['*']);

        $data = ['title' => 'Here is a New Title'];

        $response = $this->json('PUT', '/api/projects/'.$project->id, $data);

        $response->assertStatus(403);
    }

    public function test_project_creator_is_appointed_as_project_manager_by_default()
    {
        $this->seed();

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $data = $this->generate_dummy_form_data();

        $response = $this->json('POST', '/api/projects', $data);

        $response->assertStatus(201);

        $managerId = (int) json_decode($response->content())->manager;

        $this->assertEquals($managerId, $user->id);
    }

    public function test_project_creator_can_appoint_someone_else_as_project_manager()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Sanctum::actingAs($userA, ['*']);

        $data = $this->generate_dummy_form_data();

        // user A creates a new project
        $response = $this->json('POST', '/api/projects', $data);
        $response->assertStatus(201);
        $projectId = (int) json_decode($response->content())->id;

        // user A updates project so that user B is now the manager
        $response = $this->json('PUT', '/api/projects/'.$projectId, ['manager' => $userB->id]);
        $response->assertStatus(200); // should it be 204 instead?

        $response = $this->json('GET', '/api/projects/'.$projectId);
        $response->assertStatus(200);
        $managerId = (int) json_decode($response->content())->manager;

        $this->assertEquals($managerId, $userB->id);
    }
}