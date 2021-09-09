<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Rules\SkillLevel;


class UserSkillController extends Controller
{
    public function index($username)
    {
        return User::where('username', $username)->first()->skills;
    }

    public function store(Request $request)
    {
        $request->validate([
            'skill' => ['required'],
            'level' => ['required', new SkillLevel],
        ]);
        
        $user = $request->user();
        $uuid = md5(time()."".$user->username."".rand(0,9999));

        DB::table('skill_user')->insert([
            'skill' => $request['skill'],
            'level' => $request['level'],
            'user_id' => $user->id,
            'uuid' => $uuid
        ]);

        return response('Skill has been added to database', 201);
    }

    public function update(Request $request, $uuid)
    {
        Gate::authorize('isOwnerOfSkill', $uuid);

        $request->validate([
            'level' => ['required', new SkillLevel],
        ]);

        DB::table('skill_user')
            ->where('uuid', $uuid)
            ->update([
                'level' => $request['level']
            ]);

        return response('Skill has been updated', 204);
    }

    public function destroy($uuid)
    {
        Gate::authorize('isOwnerOfSkill', $uuid);

        if(DB::table('skill_user')->where('uuid', $uuid)->exists()) {
            DB::table('skill_user')->where('uuid', $uuid)->delete();
        } else {
            return response('No such record', 404);
        }

        return response('Skill has been removed from database', 204);
    }
}
