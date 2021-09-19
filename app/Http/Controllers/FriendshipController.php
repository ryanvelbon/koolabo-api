<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function follow(Request $request, $id)
    {
        $user = $request->user();

        if($user->followees->find($id))
            return response("You are already following this user.", 200); // *PENDING* what is the appropriate HTTP code to send back

        $user->followees()->attach($id);

        return response("You are now following this user.", 201);
    }

    public function unfollow(Request $request, $id)
    {
        $user = $request->user();

        if(!$user->followees->find($id))
            return response("You are not following this user.", 404);

        $user->followees()->detach($id);

        return response("You are no longer following this user.", 200);
    }
}
