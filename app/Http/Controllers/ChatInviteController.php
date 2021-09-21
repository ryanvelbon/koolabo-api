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

    /*
     * Recipient accepts or declines a ChatInvite by editing the 'status'
     */
    public function update(Request $request, $chatId, $id)
    {
        $chat = Chat::findOrFail($chatId);
        $invite = ChatInvite::findOrFail($id);

        $request->validate([
            'status' => 'required|numeric'
        ]);

        Gate::authorize('isChatInviteRecipient', $invite);

        if ($request->status == ChatInvite::STATUS_ACCEPTED) {
            $chat->participants()->attach($request->user()->id);
            $invite->status = ChatInvite::STATUS_ACCEPTED;
            $invite->save();
            $invite->delete();
            return response("you have joined the chat", 201);
        } elseif ($request->status == ChatInvite::STATUS_DECLINED) {
            $invite->status = ChatInvite::STATUS_DECLINED;
            $invite->delete();
            return response("you have declined the invite", 200);
        } else {
            return response("Cannot process your request", 409);
        }
    }

    public function destroy(Request $request, $chatId, $id)
    {
        $chat = Chat::findOrFail($chatId);
        $invite = ChatInvite::findOrFail($id);

        Gate::authorize('isChatInviteSender', $invite);

        $invite->status = ChatInvite::STATUS_CANCELLED;
        $invite->save();
        $invite->delete();

        return response("Invite has been cancelled", 200);
    }
}
