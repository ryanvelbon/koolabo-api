<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\Response;

use App\Models\User;
use App\Models\Project;

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
            return $user->id == $project->manager
                        ? Response::allow()
                        : Response::deny('You must be Project Manager to perform this action.');
        });

        Gate::define('isOwnerOfSkill', function (User $user, $uuid) {
            return DB::table('skill_user')->where('uuid', $uuid)->first()->user_id == $user->id
                        ? Response::allow()
                        : Response::deny("You do not have any skill with id : {$uuid}");
        });
    }
}
