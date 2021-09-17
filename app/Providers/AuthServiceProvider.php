<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\Response;

use App\Models\User;
use App\Models\Project;
use App\Models\JobVacancy;
use App\Models\Meetup;

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
    }
}
