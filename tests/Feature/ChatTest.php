<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Laravel\Sanctum\Sanctum;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Language;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function SKIPstart_private_chat()
    {
        $this->seed();

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $data = [
            'body' => $this->faker->paragraph()
        ];

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('POST', "/api/friendships/users/{$user->id}/message", $data);


        $data = [

        ];

        $response = $this->json('POST', "/api/chats/private");

    }

    public function test_start_group_chat()
    {
        $this->seed();

        $userA = User::factory()->create();
        
        $n = 3; // number of participants (excluding owner)

        $data = [
            'title' => "The Anti-Social Social Club",
            'participants' => User::inRandomOrder()->take($n)->pluck('id')->toArray(),
            'type' => Chat::TYPE_GROUP,
            // 'msg' => 'Is this even necessary?'
        ];

        Sanctum::actingAs($userA, ['*']);

        $response = $this->json('POST', "/api/chats", $data);
        $response->assertStatus(201);

        $this->assertEquals(1, $userA->refresh()->chats->count());

        // get ID of conversation from response
        // check participants count matches n + 1
        // check userA is admin
    }

    public function test_list_chats_for_current_user()
    {
        $this->seed();

        $chats = Chat::inRandomOrder()->take(3)->get();

        $user = User::factory()->create();

        foreach ($chats as $chat) {
            $chat->participants()->attach($user);
        }

        // guest should not be able to see chats
        $response = $this->json('GET', "/api/chats");
        $response->assertStatus(401);

        // user should see his own chats
        Sanctum::actingAs($user, ['*']);
        $response = $this->json('GET', "/api/chats");
        $response->assertStatus(200);

        // $response->dump();

        // *PENDING* assert that the correct data is returned
    }

    public function test_only_participant_can_see_chat()
    {
        $this->seed();

        $chat = Chat::where('type', Chat::TYPE_GROUP)->inRandomOrder()->first();

        foreach ($chat->participants as $user) {
            Sanctum::actingAs($user, ['*']);
            $response = $this->json('GET', "/api/chats/{$chat->id}");
            $response->assertStatus(200);
        }

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $response = $this->json('GET', "/api/chats/{$chat->id}");
        $response->assertStatus(403);

        // the same user is now added to the chat and should thus be able to see the conversation
        $chat->participants()->attach($user);
        $response = $this->json('GET', "/api/chats/{$chat->id}");
        $response->assertStatus(200);
    }

    public function test_only_owner_can_edit_group_chat()
    {
        $this->seed();

        $chat = Chat::where('type', Chat::TYPE_GROUP)->inRandomOrder()->first();

        $newTitle = "Durian Elite";
        $newDescription = "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.";
        $newLangId = Language::where('id', '!=', $chat->id)->inRandomOrder()->first()->id;

        $data = [
            'title' => $newTitle,
            'description' => $newDescription,
            'language_id' => $newLangId,
        ];

        $userA = $chat->owner; // participant & owner
        $userB = User::factory()->create(); // participant & admin
        $userC = User::factory()->create(); // participant
        $userD = User::factory()->create(); // not participant

        $chat->participants()->attach($userB, ['role' => ChatParticipant::ROLE_ADMIN]);
        $chat->participants()->attach($userC);

        foreach ([$userB, $userC, $userD] as $user) {
            Sanctum::actingAs($user, ['*']);
            $response = $this->json('PATCH', "/api/chats/{$chat->id}", $data);
            $response->assertStatus(403);
            $this->assertNotEquals($newTitle, $chat->refresh()->title);
            $this->assertNotEquals($newDescription, $chat->refresh()->description);
            $this->assertNotEquals($newLangId, $chat->refresh()->language_id);
        }

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('PATCH', "/api/chats/{$chat->id}", $data);
        $response->assertStatus(204);
        $chat->refresh();
        $this->assertEquals($newTitle, $chat->refresh()->title);
        $this->assertEquals($newDescription, $chat->refresh()->description);
        $this->assertEquals($newLangId, $chat->refresh()->language_id);
    }

    public function SKIPtest_single_field_can_be_edited()
    {

    }

    public function test_only_owner_can_delete_group_chat()
    {
        $this->seed();

        $nChats_preRequest = Chat::count();

        $chat = Chat::where('type', Chat::TYPE_GROUP)->inRandomOrder()->first();

        $userA = $chat->owner; // participant & owner
        $userB = User::factory()->create(); // participant & admin
        $userC = User::factory()->create(); // participant
        $userD = User::factory()->create(); // not participant

        $chat->participants()->attach($userB, ['role' => ChatParticipant::ROLE_ADMIN]);
        $chat->participants()->attach($userC);

        foreach ([$userB, $userC, $userD] as $user) {
            Sanctum::actingAs($user, ['*']);
            $response = $this->json('DELETE', "/api/chats/{$chat->id}");
            $response->assertStatus(403);
        }

        $this->assertEquals($nChats_preRequest, Chat::count());

        Sanctum::actingAs($userA, ['*']);
        $response = $this->json('DELETE', "/api/chats/{$chat->id}");
        $response->assertStatus(200);

        $this->assertEquals($nChats_preRequest-1, Chat::count());
    }

    public function SKIPtest_chats_are_soft_deleted()
    {

    }
}
