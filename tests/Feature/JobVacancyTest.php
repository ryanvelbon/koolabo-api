<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;
use App\Models\User;
// use App\Models\Skill;
use App\Models\Project;
use App\Models\Job;
use App\Models\City;
use App\Models\JobVacancy;

use Exception; // for testing purposes

class JobVacancyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_users_can_create_job_listing()
    {
        $this->seed();
        $nJobVacancies_preRequest = JobVacancy::count();

        $job = Job::inRandomOrder()->first();

        $data = [
            'title' => "Some Title Lorem Ipsum",
            'description' => "Hey guys! I am a Berlin-based DJ who will {$this->faker->paragraph()}",
            'city_id' => City::inRandomOrder()->first()->id,
            'job_id' => $job->id
        ];

        // user creates a listing for a job (of a project that he is managing)
        $user = $job->project->manager;
        Sanctum::actingAs($user, ['*']);        
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(201);
        $this->assertEquals($nJobVacancies_preRequest+1, JobVacancy::count());
    }

    public function test_users_cannot_create_job_listing_without_authentication()
    {
        $this->seed();
        $nJobVacancies_preRequest = JobVacancy::count();

        $data = [
            'title' => "Looking for an experienced Filmmaker who can make a music video for my upcoming techno single",
            'description' => $this->faker->paragraph(),
            'city_id' => City::inRandomOrder()->first()->id,
            'job_id'=> Job::inRandomOrder()->first()->id
        ];

        // send request without authentication
        $response = $this->json('POST', '/api/job-vacancies', $data);
        $response->assertStatus(401);
        $this->assertEquals($nJobVacancies_preRequest, JobVacancy::count());
    }

    public function test_users_can_only_create_job_listing_for_projects_which_they_are_managing()
    {
        $this->seed();

        // User A is the manager of project 1, User B is the manager of project 2
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $p1 = Project::factory()->create(['manager_id' => $userA->id]);
        $p2 = Project::factory()->create(['manager_id' => $userB->id]);

        // create some jobs for each of the two projects
        $j1 = Job::create(['craft_id' => 1, 'project_id' => $p1->id]);
        $j2 = Job::create(['craft_id' => 1, 'project_id' => $p1->id]);
        $j3 = Job::create(['craft_id' => 1, 'project_id' => $p1->id]);
        $j4 = Job::create(['craft_id' => 1, 'project_id' => $p2->id]);
        $j5 = Job::create(['craft_id' => 1, 'project_id' => $p2->id]);

        $data = [
            'title' => "Some Stupid Title",
            'description' => "Lorem ipsum dolorum if asf aufsy aiusf iastufui astfiu tasiftu asiuft iastfi uatsifu tf",
            // 'job_id' => 1,
            'city_id' => 1
        ];

        // User A should be able to create a listing for $j1, $j2 & $j3
        Sanctum::actingAs($userA, ['*']);
        $data['job_id'] = $j1->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(201);
        $data['job_id'] = $j2->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(201);
        $data['job_id'] = $j3->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(201);
        $data['job_id'] = $j4->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(403);
        $data['job_id'] = $j5->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(403);


        // User B should be able to create a listing for $j4 & $j5
        Sanctum::actingAs($userB, ['*']);
        $data['job_id'] = $j1->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(403);
        $data['job_id'] = $j2->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(403);
        $data['job_id'] = $j3->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(403);
        $data['job_id'] = $j4->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(201);
        $data['job_id'] = $j5->id;
        $response = $this->json('POST', "/api/job-vacancies", $data);
        $response->assertStatus(201);

        // They should not be able to create a listing for one another's jobs
    }

    /*
     * Job Listings should be visibile to the public and not just to authenticated users.
     */
    public function test_guest_can_read_job_listing()
    {
        $this->seed();

        $listing = JobVacancy::inRandomOrder()->first();
        $response = $this->json('GET', '/api/job-vacancies/'.$listing->id);
        $response->assertStatus(200);
    }

    public function test_guests_can_read_all_job_listings()
    {
        $this->seed();

        $response = $this->json('GET', '/api/job-vacancies');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                [
                    'id',
                    'title',
                    'description',
                    // 'slug',
                    'is_active',
                    'posted_by',
                    'city_id',
                    'job_id',
                    'ends_at',
                    'created_at',
                    'updated_at',
                ]
            ]
        );
    }

    public function test_project_manager_can_update_job_listing()
    {
        $this->seed();
        $listing = JobVacancy::factory()->create();        
        $user = $listing->job->project->manager;

        // project manager sends PATCH request with new data
        Sanctum::actingAs($user, ['*']);
        $data = ['title' => "New Title"];
        $response = $this->json('PATCH', "/api/job-vacancies/{$listing->id}", $data);
        $response->assertStatus(200);
        $response->assertJson(['title' => "New Title"]);
    }

    /*
     * User B should not be able to edit a job listing since he is neither
     * the project manager nor the author of the listing.
     */

    public function test_users_cannot_update_job_listing_of_others()
    {
        $this->seed();

        $userA = User::factory()->create();
        $listing = JobVacancy::factory()->create(['posted_by' => $userA->id,]);

        // user B will try to edit user A's listing
        $userB = User::factory()->create();
        Sanctum::actingAs($userB, ['*']);
        $data = ['title' => "New Title"];
        $response = $this->json('PATCH', "/api/job-vacancies/{$listing->id}", $data);
        $response->assertStatus(403);
    }

    public function test_users_can_delete_job_listing()
    {
        $this->seed();
        
        $user = User::factory()->create();
        $listing = JobVacancy::factory()->create(['posted_by' => $user->id]);
        $nJobVacancies_preRequest = JobVacancy::count();

        // authorized user deletes job listing
        Sanctum::actingAs($user, ['*']);
        $response = $this->json('DELETE', "/api/job-vacancies/{$listing->id}");
        $response->assertStatus(200);
        // $this->assertNull(JobVacancy::find($listing->id));
        $this->assertEquals($nJobVacancies_preRequest-1, JobVacancy::count());
        
    }

    public function SKIP_users_can_not_delete_job_listing_of_others()
    {
        $this->seed();
        
        $user = User::factory()->create();

        $listing = JobVacancy::inRandomOrder()->first();

        $response = $this->actingAs($user, 'api')->json(
            'DELETE',
            'api/job-vacancies/'.$listing->id,
        );

        $response->assertStatus(403);
        $response->assertJson(['message' => "Unauthorized action."]);

        // assert that the listing is still in the db
        $this->assertNotNull(JobVacancy::find($listing->id));
    }
}