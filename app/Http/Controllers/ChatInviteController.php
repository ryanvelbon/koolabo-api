<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatInvite;

class ChatInviteController extends Controller
{
    public function store(Request $request, $chatId)
    {
        $recipient = User::findOrFail($request->recipient_id);
        $chat = Chat::findOrFail($chatId);

        // validate data

        if($chat->type == Chat::TYPE_PRIVATE)
            return response("This is a private chat and is limited to 2 participants. Consider creating a group chat for 3+ participants", 409);

        if($chat->participants->find($recipient->id))
            return response("User is already a participant", 409);

        Gate::authorize('isChatAdmin', $chat);

        $invite = ChatInvite::create([
            'chat_id' => $chatId,
            'recipient_id' => $recipient->id,
            'sender_id' => $request->user()->id
        ]);

        return response("Invite has been sent", 201);
    }
}
