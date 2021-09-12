<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\ProjectLike;

class ProjectLikeController extends Controller
{
    public function index($projectId)
    {
        return Project::find($projectId)->likers;
    }

    public function store(Request $request, $projectId)
    {
        $user = $request->user();

        // check if user already liked this project
        if($user->projectsLiked->find($projectId))
            return "You already like this";

        // return 404 HTTP response if project does not exist
        $project = Project::findOrFail($projectId);
        
        // otherwise register the like
        $like = ProjectLike::create([
            'user_id' => $user->id,
            'project_id' => $project->id
        ]);

        return $like;
    }

    public function destroy(Request $request, $projectId)
    {
        ProjectLike::where('user_id', $request->user()->id)
                    ->where('project_id', $projectId)
                    ->firstOrFail()
                    ->delete();

        return response('You have successfully unliked this project', 204);

        /*
         * We could have alternatively made a DELETE /project-likes/{id}
         * API endpoint and delete the record like so:
         * $request->user()->projectLikes->find($id)->delete();
         * While this makes the controller logic easier, it might
         * complicate the frontend.
         */
    }
}
