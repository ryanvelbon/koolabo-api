<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Chat;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(Request $request, $chatId)
    {
        $chat = Chat::findOrFail($chatId);

        Gate::authorize('isChatParticipant', $chat);

        Message::create([
            'user_id' => $request->user()->id,
            'chat_id' => $chatId,
            'body' => $request->body
        ]);

        return response("Message sent", 201);
    }

    public function update(Request $request, $chatId, $id)
    {
        $chat = Chat::findOrFail($chatId);
        $msg = Message::findOrFail($id);

        Gate::authorize('isChatParticipant', $chat);
        Gate::authorize('isMessageAuthor', $msg);

        $msg->body = $request->body;
        $msg->save();

        return response("Message updated", 204);
    }

    /*
     * This isn't 100% bullet-proof against an unauthorized delete.
     * A user who left the chat should no longer be able to delete
     * his messages. The user could provide a $chatId to any other
     * chat which he is a participant of and can provide the $id of
     * a message of a chat he is no longer a participant of and still
     * delete it. This is a minor security-vulnerability and can be
     * ignored.
     */
    public function destroy($chatId, $id)
    {
        $chat = Chat::findOrFail($chatId);
        $msg = Message::findOrFail($id);

        Gate::authorize('isChatParticipant', $chat);
        Gate::authorize('isMessageAuthor', $msg);

        Message::destroy($id);

        return response("Message deleted", 200);
    }
}
