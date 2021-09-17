<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\Meetup;
use App\Models\User;

class MeetupTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_eloquent_relations()
    {
        $user = User::factory()->create();
        $meetup = Meetup::factory()->create();
        $meetup->images()->create(['path' => 'image.png']);
        $meetup->images()->create(['path' => 'image.png']);
        $users = User::factory(5)->create();
        $meetup->rsvps()->attach($users);

        $this->assertCount(2, $meetup->images);
        $this->assertCount(5, $meetup->rsvps);
    }

    /*
     * Retrieve specific Meetup resource with all its nested data.
     */
    public function test_show_meetup()
    {
        $n = 15; // num of users to be invited
        $users = User::factory($n)->create();
        $meetup = Meetup::factory()->create();
        $meetup->images()->create(['path' => 'image.png']);
        $meetup->rsvps()->attach($users);

        $response = $this->get("/api/meetups/{$meetup->id}");
        $response->assertStatus(200);
        $this->assertIsArray($response['images']);
        $this->assertCount(1, $response['images']);
        $this->assertIsArray($response['rsvps']);
        $this->assertCount($n, $response['rsvps']);
    }

    public function test_user_can_create_meetup()
    {
        $user = User::factory()->create();

        $nMeetups_preRequest = Meetup::count();

        $data = [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'lat' => '53.4309016',
            'lng' => '-2.9631613',
            'address_line1' => $this->faker->address(),
            'start_time' => '2022-12-1 18:00',
            'end_time' => '2022-12-1 21:00'
        ];

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('POST', "/api/meetups", $data);
        $response->assertStatus(201);
        $this->assertEquals($nMeetups_preRequest+1, Meetup::count());
    }

    public function test_guest_cannot_create_meetup()
    {
        $this->seed();

        $nMeetups_preRequest = Meetup::count();

        $data = Meetup::factory()->make()->toArray();
        unset($data['organizer_id']);

        $response = $this->json('POST', "/api/meetups", $data);
        $response->assertStatus(401);
    }

    public function test_organizer_can_edit_meetup()
    {
        $this->seed();

        $user = User::factory()->create();
        $meetup = Meetup::factory()->for($user, 'organizer')->create();

        $data = [
            'title' => $this->faker->sentence()
        ];

        Sanctum::actingAs($user, ['*']);

        $response = $this->json('PATCH', "/api/meetups/{$meetup->id}", $data);
        $response->assertStatus(200);
    }

    public function test_meetup_can_only_be_edited_by_organizer()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $meetup = Meetup::factory()->for($userA, 'organizer')->create();
        $newTitle = $this->faker->sentence();

        $data = [
            'title' => $newTitle
        ];

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('PATCH', "/api/meetups/{$meetup->id}", $data);
        $response->assertStatus(403);
        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('PATCH', "/api/meetups/{$meetup->id}", $data);
        $response->assertStatus(200);
        $meetup->refresh();
        $this->assertEquals($newTitle, $meetup->title);
    }

    public function test_organizer_can_delete_meetup()
    {
        $user = User::factory()->create();
        $meetup = Meetup::factory()->for($user, 'organizer')->create();

        $nMeetups_preRequest = Meetup::count();

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('DELETE', "/api/meetups/{$meetup->id}");
        $response->assertStatus(200);
        $this->assertEquals($nMeetups_preRequest-1, Meetup::count());
    }

    public function test_meetup_can_only_be_deleted_by_organizer()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $meetup = Meetup::factory()->for($userA, 'organizer')->create();

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('DELETE', "/api/meetups/{$meetup->id}");
        $response->assertStatus(403);
        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('DELETE', "/api/meetups/{$meetup->id}");
        $response->assertStatus(200);
    }

    public function test_list_meetups()
    {
        $n = 3;

        User::factory(5)->create();
        Meetup::factory($n)->create();

        $response = $this->get('/api/meetups');
        $response->assertStatus(200);
        $response->assertJsonCount($n, 'data');
    }

    public function test_list_meetups_paginated()
    {
        $n = 100;

        User::factory(5)->create();
        Meetup::factory($n)->create();

        $response = $this->get('api/meetups');
        $response->assertStatus(200);
        $this->assertNotNull($response->json('data')[0]['id']);
        $this->assertNotNull($response->json('meta'));
        $this->assertNotNull($response->json('links'));
    }

    public function test_filter_meetups()
    {
        $m = 3; // number of meetups created by others
        $n = 2; // number of meetups created by our target user

        User::factory(5)->create();
        Meetup::factory($m)->create();

        $user = User::factory()->create();

        Meetup::factory($n)->for($user, 'organizer')->create();

        $response = $this->get('/api/meetups');
        $response->assertJsonCount($m+$n, 'data');
        $response = $this->get("/api/meetups?organizer_id=$user->id");
        $response->assertJsonCount($n, 'data');
        
    }

    /*
     * This test must be run with a MySQL test database
     */
    public function SKIPtest_orders_by_distance_when_provided_coordinates()
    {
        User::factory(1)->create();

        Meetup::factory()->create([
            'lat' => '41.2778868',
            'lng' => '1.9540305',
            'title' => 'Castelldefels'
        ]);

        Meetup::factory()->create([
            'lat' => '40.4378698',
            'lng' => '-3.8196209',
            'title' => 'Madrid'
        ]);

        Meetup::factory()->create([
            'lat' => '41.380898',
            'lng' => '2.122820',
            'title' => 'Camp Nou'
        ]);

        Meetup::factory()->create([
            'lat' => '41.6516859',
            'lng' => '-0.9300003',
            'title' => 'Zaragoza'
        ]);

        Meetup::factory()->create([
            'lat' => '41.403160',
            'lng' => '2.173660',
            'title' => 'Sagrada Familia'
        ]);

        // we are at Placa de Catalunya
        $response = $this->get('/api/meetups?lat=41.3870792&lng=2.1674431');

        $response->assertOk()
            ->assertJsonPath('data.0.title', 'Sagrada Familia')
            ->assertJsonPath('data.1.title', 'Camp Nou')
            ->assertJsonPath('data.2.title', 'Castelldefels')
            ->assertJsonPath('data.3.title', 'Zaragoza')
            ->assertJsonPath('data.4.title', 'Madrid');
    }
}
