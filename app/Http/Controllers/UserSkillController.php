<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Skill;
use App\Models\UserSkill;

use App\Rules\SkillLevel;

/*
|--------------------------------------------------------------------------
| READ THIS before refactoring any code
|--------------------------------------------------------------------------
|
| uuid field and isOwnerOfSkill gate are no longer necessary 
| 
| 
|
*/


class UserSkillController extends Controller
{
    public function index($userId)
    {
        return User::find($userId)->skills;
    }

    /*
     * Pending:
     * Tried the following, for a case insensitive search, but didn't work:
     * if($user->skills->where(DB::raw('lower(title)', 'like', '%' . strtolower($request['skill']) . '%'))->first())
     *
     */

    public function store(Request $request)
    {
        $request->validate([
            'skill' => ['required'],
            'level' => ['required', new SkillLevel],
        ]);

        $user = $request->user();
        $skill = Skill::where('title', $request['skill'])->first();

        // if skill is not recognized (does not exist in `skills` table), add to database
        if(!$skill)
            $skill = Skill::create([
                'title' => $request['skill'],
                'created_by' => $user->id
            ]);
        
        if($user->skills->find($skill->id))
            return response("You already have this skill", 200);

        $user->skills()->attach($skill->id, ['level' => $request['level']]);

        return response('Your skill has been registered', 201);
    }


    public function update(Request $request, $skillId)
    {
        $request->validate([
            'level' => ['required', new SkillLevel],
        ]);

        $user = $request->user();

        if(!$user->skills->find($skillId))
            return response('No such record', 404);

        $user->skills()->updateExistingPivot($skillId, ['level' => $request['level']]);

        return response('Your skill has been updated.', 200);
    }


    public function destroy(Request $request, $skillId)
    {
        if($request->user()->skills()->detach($skillId))
            return response('Your skill has been removed', 200);
        else
            return response('No such record', 404);
    }
}