<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chat;
use App\Models\ChatInvite;
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

    public function test_sender_can_cancel_invite()
    {
        $this->seed();

        $chat = Chat::where('type', Chat::TYPE_GROUP)->inRandomOrder()->first();
        $admin = User::factory()->create();
        $chat->participants()->attach($admin, ['role' => ChatParticipant::ROLE_ADMIN]);
        $nParticipants_preRequest = $chat->participants->count();

        $user = User::factory()->create();

        $invite = ChatInvite::create([
            'chat_id' => $chat->id,
            'recipient_id' => $user->id,
            'sender_id' => $admin->id
        ]);

        $user->refresh();
        $nInvites_preRequest = $user->chatInvites->count();
        $nChats_preRequest = $user->chats->count();

        Sanctum::actingAs($admin, ['*']);
        $response = $this->json('DELETE', "/api/chats/{$chat->id}/invites/{$invite->id}");
        $response->assertStatus(200);

        $user->refresh();
        $this->assertEquals($nInvites_preRequest-1, $user->chatInvites->count());
        $this->assertEquals($nChats_preRequest, $user->chats->count());
        $this->assertEquals($nParticipants_preRequest, $chat->refresh()->participants->count());
    }

    public function test_recipient_can_accept_invite_and_is_successfully_added_to_chat()
    {
        $this->seed();

        $chat = Chat::where('type', Chat::TYPE_GROUP)->inRandomOrder()->first();
        $admin = User::factory()->create();
        $chat->participants()->attach($admin, ['role' => ChatParticipant::ROLE_ADMIN]);
        $nParticipants_preRequest = $chat->participants->count();

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $invite = ChatInvite::create([
            'chat_id' => $chat->id,
            'recipient_id' => $userA->id,
            'sender_id' => $admin->id
        ]);

        $userA->refresh();
        $nInvites_preRequest = $userA->chatInvites->count();
        $nChats_preRequest = $userA->chats->count();

        $data = ['status' => ChatInvite::STATUS_ACCEPTED];

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('PATCH', "/api/chats/{$chat->id}/invites/{$invite->id}", $data);
        $response->assertStatus(403);

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('PATCH', "/api/chats/{$chat->id}/invites/{$invite->id}", $data);
        $response->assertStatus(201);
        $userA->refresh();
        $this->assertEquals($nInvites_preRequest-1, $userA->chatInvites->count());
        $this->assertEquals($nChats_preRequest+1, $userA->chats->count());
        $this->assertEquals($nParticipants_preRequest+1, $chat->refresh()->participants->count());
    }

    public function test_recipient_can_decline_invite()
    {
        $this->seed();

        $chat = Chat::where('type', Chat::TYPE_GROUP)->inRandomOrder()->first();
        $admin = User::factory()->create();
        $chat->participants()->attach($admin, ['role' => ChatParticipant::ROLE_ADMIN]);
        $nParticipants_preRequest = $chat->participants->count();

        $user = User::factory()->create();

        $invite = ChatInvite::create([
            'chat_id' => $chat->id,
            'recipient_id' => $user->id,
            'sender_id' => $admin->id
        ]);

        $user->refresh();
        $nInvites_preRequest = $user->chatInvites->count();
        $nChats_preRequest = $user->chats->count();

        $data = ['status' => ChatInvite::STATUS_DECLINED];

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('PATCH', "/api/chats/{$chat->id}/invites/{$invite->id}", $data);
        $response->assertStatus(200);
        $user->refresh();
        $this->assertEquals($nInvites_preRequest-1, $user->chatInvites->count());
        $this->assertEquals($nChats_preRequest, $user->chats->count());
        $this->assertEquals($nParticipants_preRequest, $chat->refresh()->participants->count());
    }
}
