<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;
use App\Models\User;

class FriendshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_follow_someone()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        
        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('POST', "/api/friendships/users/{$userB->id}/follow");
        $response->assertStatus(201);
    }

    public function test_user_can_unfollow_someone()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $userB->followers()->attach($userA);

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('POST', "/api/friendships/users/{$userB->id}/unfollow");
        $response->assertStatus(200);

        $this->assertNull($userA->refresh()->followees->find($userB->id));
    }

    public function SKIPtest_user_can_block_someone()
    {

    }

    public function SKIPtest_user_can_unblock_someone()
    {

    }

    public function SKIPtest_user_can_mute_someone()
    {

    }

    public function SKIPtest_user_can_unmute_someone()
    {

    }

    public function SKIPtest_list_someones_followers()
    {

    }

    public function SKIPtest_list_someones_followees()
    {

    }
}
