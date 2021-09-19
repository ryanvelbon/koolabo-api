<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Project;
use App\Models\ProjectInvite;

class ProjectInviteController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->projectInvites;
    }


    public function show($projectId, $id)
    {
        $invite = ProjectInvite::findOrFail($id);

        Gate::authorize('canSeeProjectInvite', $invite);

        return $invite->load('project');
    }


    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        Gate::authorize('isManager', $project);

        $request->validate([
            'recipient_id' => 'required', // *PENDING* Make sure not already member or invite not already sent
            'msg' => 'required|min:30|max:2000'
        ]);

        $data = $request->all();
        $data['sender_id'] = $request->user()->id;
        $data['project_id'] = $projectId;
        // $data['status'] = ProjectInvite::STATUS_SENT;

        return ProjectInvite::create($data);
    }


    public function update(Request $request, $projectId, $id)
    {
        /*
         * *REVISE* refactor to ensure that:
         * sender can only 'CANCEL' an invite
         * recipient can only 'ACCEPT' or 'DECLINE' an invite
         */

        $invite = ProjectInvite::findOrFail($id);

        Gate::authorize('canSeeProjectInvite', $invite);

        $request->validate([
            'status' => 'required|numeric|min:1|max:5'
        ]);

        $invite->status = $request->status;
        // $invite->last_updated_by = $request->user()->id; *REVISE*
        $invite->save();

        return response('Project invitation status updated', 200);
    }


    public function destroy($projectId, $id)
    {
        $invite = ProjectInvite::findOrFail($id); // returns 404 if record doesn't exist

        $project = Project::findOrFail($projectId);

        Gate::authorize('isManager', $project); // *REVISE* isProjectInviteSender instead?

        $invite->status = ProjectInvite::STATUS_CANCELLED;
        $invite->delete();

        return response('Project invitation cancelled', 200);
    }
}
