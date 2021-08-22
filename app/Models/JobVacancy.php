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

    // not necessary? Can be derived from an SQL JOIN
    public function postedBy(){
    	return $this->belongsTo('App\Models\User');
    }

    public function city()
    {
        return $this->hasOne('App\Models\City');
    }

    // not necessary? Can be derived from an SQL JOIN
    public function topics(){
        return $this->belongsToMany('App\Models\Topic');
    }

    public function skills(){
        return $this->belongsToMany('App\Models\Skill');
    }
}
