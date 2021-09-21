<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatParticipant;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        // *PENDING* add filtering

        return $request->user()->chats;
    }

    public function show(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        Gate::authorize('isChatParticipant', $chat);

        return $chat->load('participants', 'messages');
    }

    public function store(Request $request)
    {
        $request->validate([
            'participants' => 'required',
            'title' => 'required'
        ]);

        $user = $request->user();

        $participants = $request->participants;

        User::findOrFail($participants);

        $chat = Chat::create([
            'type' => Chat::TYPE_GROUP
        ]);

        ChatParticipant::create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'role' => ChatParticipant::ROLE_OWNER
        ]);

        $chat->participants()->attach($participants);

        $chat->refresh();

        return $chat;


        // EXTRA COLUMNS
        // 'title' => $request->title,
    }

    public function update(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        Gate::authorize('isChatOwner', $chat);

        // *PENDING* data validation

        if($request->title)
            $chat->title = $request->title;
        if($request->description)
            $chat->description = $request->description;
        if($request->language_id)
            $chat->language_id = $request->language_id;

        $chat->save();

        return response("Chat details successfully updated", 204);
    }

    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);

        Gate::authorize('isChatOwner', $chat);

        Chat::destroy($id);

        return response("Chat deleted", 200);
    }
}
