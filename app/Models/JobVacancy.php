<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function postedBy()
    {
    	return $this->belongsTo(User::class, 'posted_by');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // public function topics(){
    //     return $this->belongsToMany(Topic::class);
    // }

    public function skills(){
        return $this->belongsToMany(Skill::class);
    }
}
