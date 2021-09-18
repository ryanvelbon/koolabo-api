<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobVacancy extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'slug',
        'title',
        'description',
        'is_active',
        'job_id',
        'city_id',
        'ends_at'
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function postedBy(): BelongsTo
    {
    	return $this->belongsTo(User::class, 'posted_by');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_vacancy_skills');
    }
}
