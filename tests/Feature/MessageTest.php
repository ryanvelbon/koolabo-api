<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Message;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_participants_can_send_message()
    {
        $this->seed();

        $users = User::inRandomOrder()->take(5)->get();

        $userA = $users->pop();
        $userB = $users->pop();
        $userC = $users->pop();
        $userD = $users->pop();
        $userE = $users->pop();

        $chat = Chat::create([
            'type' => Chat::TYPE_GROUP,
            'owner_id' => $userA->id
        ]);

        $chat->participants()->attach($userA, ['role' => ChatParticipant::ROLE_OWNER]);
        $chat->participants()->attach($userB, ['role' => ChatParticipant::ROLE_ADMIN]);
        $chat->participants()->attach($userC, ['role' => ChatParticipant::ROLE_USER]);

        $data = ["body" => "Hello World!"];

        // only users A, B & C should be able to send messages in this chat

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('POST', "/api/chats/{$chat->id}/messages", $data);
        $response->assertStatus(201);

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('POST', "/api/chats/{$chat->id}/messages", $data);
        $response->assertStatus(201);

        Sanctum::actingAs($userC, ['*']);
        $response = $this->json('POST', "/api/chats/{$chat->id}/messages", $data);
        $response->assertStatus(201);

        Sanctum::actingAs($userD, ['*']);
        $response = $this->json('POST', "/api/chats/{$chat->id}/messages", $data);
        $response->assertStatus(403);

        Sanctum::actingAs($userE, ['*']);
        $response = $this->json('POST', "/api/chats/{$chat->id}/messages", $data);
        $response->assertStatus(403);
    }

    public function test_only_author_can_edit_message()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();

        $chat = Chat::inRandomOrder()->first();

        $chat->participants()->attach($userA);
        $chat->participants()->attach($userB);

        $oldMsg = "This is the original message.";
        $newMsg = "This is the newly updated message.";

        $msg = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $userA->id,
            'body' => $oldMsg
        ]);

        $data = ['body' => $newMsg];

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('PATCH', "/api/chats/{$chat->id}/messages/{$msg->id}", $data);
        $response->assertStatus(403);
        $this->assertEquals($oldMsg, $msg->refresh()->body);

        Sanctum::actingAs($userC, ['*']);
        $response = $this->json('PATCH', "/api/chats/{$chat->id}/messages/{$msg->id}", $data);
        $response->assertStatus(403);
        $this->assertEquals($oldMsg, $msg->refresh()->body);

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('PATCH', "/api/chats/{$chat->id}/messages/{$msg->id}", $data);
        $response->assertStatus(204);
        $this->assertEquals($newMsg, $msg->refresh()->body);

    }

    public function test_author_cannot_edit_message_if_no_longer_participant()
    {
        $this->seed();

        $chat = Chat::inRandomOrder()->first();
        $user = User::factory()->create();
        $chat->participants()->attach($user);

        $oldMsg = "You are a looser!";
        $newMsgA = "You are a loser!";
        $newMsgB = "You are a loser! (edit: spelling)";

        $msg = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'body' => $oldMsg
        ]);

        $data = ['body' => $newMsgA];

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('PATCH', "/api/chats/{$chat->id}/messages/{$msg->id}", $data);
        $response->assertStatus(204);
        $this->assertEquals($newMsgA, $msg->refresh()->body);

        // user is removed from chat and thus should no longer be able to edit his messages
        $chat->participants()->detach($user);

        $data = ['body' => $newMsgB];

        $response = $this->json('PATCH', "/api/chats/{$chat->id}/messages/{$msg->id}", $data);
        $response->assertStatus(403);
        $this->assertEquals($newMsgA, $msg->refresh()->body);
    }

    /*
     * *PENDING* should Owner and Admin be able to delete 3rd party messages?
     */
    public function test_only_author_can_delete_message()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();

        $chat = Chat::inRandomOrder()->first();

        $chat->participants()->attach($userA);
        $chat->participants()->attach($userB);

        $msg = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $userA->id,
            'body' => "This was written by User A and only he can delete it."
        ]);

        $nMessages_preRequest = $chat->refresh()->messages->count();

        Sanctum::actingAs($userB, ['*']);
        $response = $this->json('DELETE', "/api/chats/{$chat->id}/messages/{$msg->id}");
        $response->assertStatus(403);
        $this->assertEquals($nMessages_preRequest, $chat->refresh()->messages->count());

        Sanctum::actingAs($userC, ['*']);
        $response = $this->json('DELETE', "/api/chats/{$chat->id}/messages/{$msg->id}");
        $response->assertStatus(403);
        $this->assertEquals($nMessages_preRequest, $chat->refresh()->messages->count());

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('DELETE', "/api/chats/{$chat->id}/messages/{$msg->id}");
        $response->assertStatus(200);
        $this->assertEquals($nMessages_preRequest-1, $chat->refresh()->messages->count());
    }

    public function test_author_cannot_delete_message_if_no_longer_participant()
    {
        $this->seed();

        $chat = Chat::inRandomOrder()->first();
        $user = User::factory()->create();
        $chat->participants()->attach($user);

        $msgA = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'body' => "I love avocado juice with chocolate"
        ]);

        $msgB = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'body' => "Simplicity is the ultimate sophistication."
        ]);

        $nMessages_preRequest = $chat->refresh()->messages->count();

        Sanctum::actingAs($user, ['*']);

        $response = $this->json('DELETE', "/api/chats/{$chat->id}/messages/{$msgA->id}");
        $response->assertStatus(200);

        // user is removed from chat and thus should no longer be able to delete his messages
        $chat->participants()->detach($user);

        $response = $this->json('DELETE', "/api/chats/{$chat->id}/messages/{$msgB->id}");
        $response->assertStatus(403);
        
        // there should only be 1 message less (not 2)
        $this->assertEquals($nMessages_preRequest-1, $chat->refresh()->messages->count());
    }
}
