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
        return $this->belongsTo('App\Models\Job');
    }

    public function postedBy()
    {
    	return $this->belongsTo('App\Models\User', 'posted_by');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    // public function topics(){
    //     return $this->belongsToMany('App\Models\Topic');
    // }

    public function skills(){
        return $this->belongsToMany('App\Models\Skill');
    }
}
