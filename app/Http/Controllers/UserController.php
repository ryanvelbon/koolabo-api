<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index(): JsonResource
    {
        $users = User::query()
            ->with(['profile', 'topics', 'skills'])
            ->withCount('followers', 'followees')
            ->paginate(20);

        return UserResource::collection($users);

    }

    public function show($id)
    {
        $user = User::query()
            ->withCount(['followers', 'followees'])
            ->with(['profile', 'images', 'topics', 'skills'])
            ->find($id);

        return UserResource::make($user);
    }
}
