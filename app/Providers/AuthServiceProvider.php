<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\Response;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectInvite;
use App\Models\JobVacancy;
use App\Models\Meetup;
use App\Models\Chat;
use App\Models\Message;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isManager', function (User $user, Project $project) {
            return $user->id == $project->manager_id
                        ? Response::allow()
                        : Response::deny('You must be Project Manager to perform this action.');
        });

        Gate::define('isOrganizer', function (User $user, Meetup $meetup) {
            return $user->id == $meetup->organizer_id
                        ? Response::allow()
                        : Response::deny('You must be Event Organizer to perform this action.');
        });

        Gate::define('canEditDeleteJobListing', function (User $user, JobVacancy $listing) {
            return $user->id == $listing->posted_by || $user->id == $listing->job->project->manager->id
                        ? Response::allow()
                        : Response::deny('Only project manager and listing author can perform this action.');
        });

        Gate::define('canSeeProjectInvite', function (User $user, ProjectInvite $invite) {
            return $user->id == $invite->sender_id || $user->id == $invite->recipient_id || $user->id == $invite->project->manager_id
                        ? Response::allow()
                        : Response::deny('You are not authorized to see this invititaion.');
        });

        Gate::define('isChatOwner', function (User $user, Chat $chat) {
            return $user->id == $chat->owner_id
                        ? Response::allow()
                        : Response::deny('Only chat owner can perform this action.');
        });

        Gate::define('isChatAdmin', function (User $user, Chat $chat) {
            return $chat->participants->where('role', ChatParticipant::ROLE_ADMIN)->find($user->id)
                        ? Response::allow()
                        : Response::deny('Only chat admin can perform this action.');
        });

        Gate::define('isChatParticipant', function (User $user, Chat $chat) {
            return $chat->participants->find($user->id)
                        ? Response::allow()
                        : Response::deny('You must be a participant of this conversation to perform this action.');
        });

        Gate::define('isMessageAuthor', function (User $user, Message $message) {
            return $user->id == $message->user_id
                        ? Response::allow()
                        : Response::deny('Only message author can perform this action.');
        });
    }
}
