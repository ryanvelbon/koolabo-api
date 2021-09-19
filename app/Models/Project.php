<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Models\User;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'description',
        'created_by',
    	'manager_id',
    	'type',
    	'projected_timeline',
    	'planned_start_date',
    	'planned_end_date'
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

    public function invites(): HasMany
    {
        return $this->hasMany(ProjectInvite::class, 'project_id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'resource');
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'project_topics');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_likes')->withTimestamps();
    }    

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_followers')->withTimestamps();
    }

    /**
     * Returns number of likes received by this project
     * PENDING: Can be optimized.
     * @return int
     */
    public function getNLikesAttribute()
    {
        return $this->likes->count();
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
}
