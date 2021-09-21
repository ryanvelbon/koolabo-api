<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chat;
// use App\Models\ChatInvite;
use App\Models\ChatParticipant;

class ChatInviteTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Only admins and owner can invite users to join a group chat
     */
    public function test_only_admin_can_invite_users()
    {
        $this->seed();

        $chat = Chat::inRandomOrder()->first();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $chat->participants()->attach($userA, ['role' => ChatParticipant::ROLE_ADMIN]);
        $chat->participants()->attach($userB);

        $recipient = User::factory()->create();
        $this->assertEquals(0, $recipient->refresh()->chatInvites->count());

        $data = [
            'recipient_id' => $recipient->id
        ];

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('POST', "/api/chats/{$chat->id}/invites", $data);
        $response->assertStatus(403);

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('POST', "/api/chats/{$chat->id}/invites", $data);
        $response->assertStatus(201);

        $this->assertEquals(1, $recipient->refresh()->chatInvites->count());

    }
}
