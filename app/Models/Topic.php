<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MBelongsToMany;

class Topic extends Model
{
    /*
     * Get all Users that are interested in this Topic.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_topics');
    }

    /*
     * Get all Projects that are related to this Topic.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_topics');
    }
}
