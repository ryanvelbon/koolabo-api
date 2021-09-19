<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectInvite;

class ProjectInviteTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_recipient_can_accept_invite()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $project = Project::factory()->for($userA, 'manager')->create();

        $invite = ProjectInvite::create([
            'sender_id' => $userA->id,
            'recipient_id' => $userB->id,
            'project_id' => $project->id,
            'msg' => $this->faker->paragraph()
        ]);

        $updatedStatus = ProjectInvite::STATUS_ACCEPTED;

        $data = [
            'status' => $updatedStatus
        ];

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('PATCH', "api/projects/{$project->id}/invites/{$invite->id}", $data);
        $response->assertStatus(200);
        $this->assertEquals($updatedStatus, $invite->refresh()->status);
    }

    public function SKIPtest_only_status_can_be_updated()
    {

    }

    public function SKIPtest_recipient_can_decline_invite()
    {
        
    }

    public function SKIPtest_sender_can_cancel_invite()
    {
        
    }

    public function test_eloquent_relations()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $project = Project::factory()->for($userA, 'manager')->create();

        $invite = ProjectInvite::create([
            'sender_id' => $userA->id,
            'recipient_id' => $userB->id,
            'project_id' => $project->id,
            'msg' => $this->faker->paragraph()
        ]);

        $invite = ProjectInvite::find($invite->id);

        $this->assertEquals($userA->id, $invite->sender->id);
        $this->assertEquals($userB->id, $invite->recipient->id);
        $this->assertEquals($project->id, $invite->project->id);

    }

    public function test_list_invites()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $projects = Project::factory(7)->for($userA, 'manager')->create();

        foreach ($projects as $project) {
            ProjectInvite::create([
                'sender_id' => $userA->id,
                'recipient_id' => $userB->id,
                'project_id' => $project->id,
                'msg' => $this->faker->paragraph()
            ]);
        }

        $response = $this->json('GET', "/api/users/me/project-invites");
        $response->assertStatus(401);

        Sanctum::actingAs($userB);
        $response = $this->json('GET', "/api/users/me/project-invites");
        $response->assertStatus(200);

        // *PENDING* assert count matches
    }

    public function test_show_invite()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $userX = User::factory()->create();

        $project = Project::factory()->for($userA, 'manager')->create();
        
        $invite = ProjectInvite::create([
            'sender_id' => $userA->id,
            'recipient_id' => $userB->id,
            'project_id' => $project->id,
            'msg' => $this->faker->paragraph()
        ]);
        

        // as guest
        $response = $this->json('GET', "/api/projects/{$project->id}/invites/{$invite->id}");
        $response->assertStatus(401);

        // as 3rd party user (authenticated but not authorized to see invite)
        Sanctum::actingAs($userX, ['*']);
        $response = $this->json('GET', "/api/projects/{$project->id}/invites/{$invite->id}");
        $response->assertStatus(403);

        // as sender
        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('GET', "/api/projects/{$project->id}/invites/{$invite->id}");
        $response->assertStatus(200);

        // as recipient
        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('GET', "/api/projects/{$project->id}/invites/{$invite->id}");
        $response->assertStatus(200);
    }

    public function test_only_project_manager_can_invite()
    {
        $this->seed();

        $userA = User::factory()->create(); // the manager
        $userB = User::factory()->create(); // a project member
        $userX = User::factory()->create(); // the invitee

        $project = Project::factory()->for($userA, 'manager')->create();
        $project->members()->attach($userB);        

        $data = [
            'recipient_id' => $userX->id,
            'msg' => $this->faker->paragraph()
        ];

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('POST', "/api/projects/{$project->id}/invites", $data);
        $response->assertStatus(403);

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('POST', "/api/projects/{$project->id}/invites", $data);
        $response->assertStatus(201);
    }

    public function test_only_project_manager_can_cancel_invite()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $project = Project::factory()->for($userA, 'manager')->create();

        $invite = ProjectInvite::create([
            'sender_id' => $userA->id,
            'recipient_id' => $userB->id,
            'project_id' => $project->id,
            'msg' => $this->faker->paragraph()
        ]);

        $nInvites_preRequest = ProjectInvite::count();

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('DELETE', "/api/projects/{$project->id}/invites/{$invite->id}");
        $response->assertStatus(403);
        $this->assertEquals($nInvites_preRequest, ProjectInvite::count());

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('DELETE', "/api/projects/{$project->id}/invites/{$invite->id}");
        $response->assertStatus(200);
        $this->assertEquals($nInvites_preRequest-1, ProjectInvite::count());
    }
}
