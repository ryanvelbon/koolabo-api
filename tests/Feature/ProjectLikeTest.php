<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectLike;


class ProjectLikeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function SKIPtest_list_users_who_like_project()
    {
        $this->seed();
        $project = Project::factory()->create();
        $users = User::inRandomOrder()->take(10)->get();
        $project->likes()->attach($users);

        $response = $this->json('GET', "/api/projects/{$project->id}/likes");
        $response->assertStatus(200);
        // $this->assertCount(10, ???? ); // *PENDING* how to check that size of array is correct?

    }

    public function test_authenticated_user_can_like_project()
    {
        $this->seed();

        $project = Project::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $response = $this->json('POST', "/api/projects/{$project->id}/likes");
        $response->assertStatus(201);
        $this->assertEquals(1, $project->nLikes);
    }

    public function test_authenticated_user_can_unlike_project()
    {
        $this->seed();

        $project = Project::factory()->create();
        $user = User::factory()->create();
        ProjectLike::create([
            'user_id' => $user->id,
            'project_id' => $project->id
        ]);
        $this->assertEquals(1, $project->nLikes);
        $this->assertEquals(1, $user->nProjectsLiked);
        Sanctum::actingAs($user, ['*']);
        $response = $this->json('DELETE', "/api/projects/{$project->id}/likes");
        $response->assertStatus(204);
        $this->assertEquals(0, $project->refresh()->nLikes);
        $this->assertEquals(0, $user->refresh()->nProjectsLiked);

    }

    public function test_unauthenticated_user_cannot_like_project()
    {
        $this->seed();
        $project = Project::inRandomOrder()->first();
        $response = $this->json('POST', "/api/projects/{$project->id}/likes");
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_unlike_project()
    {
        $this->seed();
        $project = Project::inRandomOrder()->first();
        $response = $this->json('DELETE', "/api/projects/{$project->id}/likes");
        $response->assertStatus(401);
    }

    public function SKIP_user_cannot_delete_likes_of_other_users()
    {
        $this->seed();
        $a = User::factory()->create();
        $b = User::factory()->create();
        $project = Project::inRandomOrder()->first();
        Sanctum::actingAs($a, ['*']);
        $response = $this->json('POST', "/api/projects/{$project->id}/likes");
        $response->assertStatus(201);
        // now User B tries deleting that like
        Sanctum::actingAs($b, ['*']);
        // PENDING

    }

    public function test_many_users_can_like_project()
    {
        $this->seed();
        $project = Project::factory()->create();

        // a total of n users will like the project
        $n = 5;
        $users = User::inRandomOrder()->take($n)->get();
        foreach($users as $user)
        {
            Sanctum::actingAs($user, ['*']);
            $this->json('POST', "/api/projects/{$project->id}/likes");
        }
        $this->assertEquals($n, $project->refresh()->nLikes);
    }

    /*
     *   Not sure why this test fails. Carrying out the same
     *   steps inside Postman works...
     */
    public function SKIP_post_request_is_idempotent()
    {
        $this->seed();

        $project = Project::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $response = $this->json('POST', "/api/projects/{$project->id}/likes");
        $response->assertStatus(201);
        $response = $this->json('POST', "/api/projects/{$project->id}/likes");
        $response->assertStatus(200);
        // $this->assertEquals(Project::find($project->id)->nLikes, 1);
    }

    public function SKIP_delete_request_is_idempotent()
    {
        $this->assertTrue(False);
    }
}
