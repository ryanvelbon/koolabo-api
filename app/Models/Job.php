<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
    	'craft_id',
    	'project_id',
    	'assigned_to'
    ];

    // allow an unoccupied job to be listed again after the listing expires
    public function listings(): HasMany
    {
    	return $this->hasMany(JobVacancy::class);
    }

    public function craft(): BelongsTo
    {
        return $this->belongsTo(Craft::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
