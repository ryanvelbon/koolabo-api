<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;

use App\Models\User;
use App\Models\Project;
use App\Models\Craft;
use App\Models\Job;

class JobTest extends TestCase
{
    use RefreshDatabase;
    // use WithFaker;

    public function test_project_manager_can_create_job()
    {
        $this->seed();

        $project = Project::inRandomOrder()->first();
        $nJobs = $project->jobs->count();
        $user = $project->manager;
        Sanctum::actingAs($user, ['*']);

        $data = [
            'craft_id' => Craft::inRandomOrder()->first()->id,
            'project_id' => $project->id,
            // 'assigned_to' => User::inRandomOrder()->first()->id
        ];

        $response = $this->json('POST', '/api/jobs', $data);

        $response->assertStatus(201);
        $this->assertEquals(
            Project::find($project->id)->jobs->count(),
            $nJobs+1
        );
    }

    public function test_project_manager_can_delete_job()
    {
        $this->seed();

        $job = Job::inRandomOrder()->first();
        $project = Project::find($job->project_id);
        $nJobs = $project->jobs->count();

        $user = $project->manager;
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('DELETE', '/api/jobs/'.$job->id);
        $response->assertStatus(200);
        $this->assertEquals(Job::find($job->id), null);
        $this->assertEquals(
            Project::find($project->id)->jobs->count(),
            $nJobs-1
        );
    }

    public function test_project_manager_can_edit_job()
    {
        $this->seed();

        $job = Job::inRandomOrder()->first();
        $project = Project::find($job->project_id);
        
        $user = $project->manager;
        Sanctum::actingAs($user, ['*']);

        $data = [
            'craft_id' => Craft::inRandomOrder()->first()->id,
            'assigned_to' => User::inRandomOrder()->first()->id
        ];

        $response = $this->json('PATCH', '/api/jobs/'.$job->id, $data);
        $response->assertStatus(200); // or 204
    }

    /*
    public function test_project_manager_can_assign_job_to_team_member()
    {
        $this->assertTrue(False);
    }

    public function test_project_manager_can_reassign_job_from_one_member_to_another()
    {
        $this->assertTrue(False);
    }

    public function test_project_manager_can_not_assign_job_to_user_who_is_not_team_member()
    {
        $this->assertTrue(False);
    }

    public function test_user_can_apply_for_job()
    {
        $this->assertTrue(False);
    }

    public function test_project_manager_can_reject_application()
    {
        $this->assertTrue(False);
    }

    public function test_project_manager_can_accept_application()
    {
        $this->assertTrue(False);
    }

    public function test_applicant_is_notified_when_application_is_approved()
    {
        $this->assertTrue(False);
    }
    */
}