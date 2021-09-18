<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User;

class Skill extends Model
{
    protected $fillable = [
        'title',
        'created_by'
    ];

    protected $hidden = [
        'created_by',
        'created_at',
        'updated_at',
        'pivot'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills');
    }
}
