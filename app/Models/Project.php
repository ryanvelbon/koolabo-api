<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'description',
        'created_by',
    	'manager',
    	'type',
    	'projected_timeline',
    	'planned_start_date',
    	'planned_end_date'
    ];

    public function manager()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function jobs()
    {
        // consider using a VIEW TABLE instead

        return $this->hasMany('App\Models\Job');
    }

    public function getTeamAttribute()
    {
        // consider using a VIEW TABLE instead

        $jobs = $this->jobs;

        $members = array();

        foreach ($jobs as $job)
        {
            $user = User::find($job->assigned_to);
            array_push($members, $user);
        }

        return $members;
    }

    public function getFollowersAttribute()
    {
        return "-pending implementation-";
    }

    // users who like this project (upvoters?)
    public function likers()
    {
        return $this->belongsToMany(User::class, 'project_likes')->withTimestamps();
    }

    /**
     * Returns number of likes received by this project
     * PENDING: Can be optimized.
     * @return int
     */
    public function getNLikesAttribute()
    {
        return $this->likers->count();
    }
}
