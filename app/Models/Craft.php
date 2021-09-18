<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Craft extends Model
{
    public function Jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function Users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_crafts');
    }
}
