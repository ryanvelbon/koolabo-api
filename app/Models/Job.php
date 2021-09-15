<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
    	'craft_id',
    	'project_id',
    	'assigned_to'
    ];

    // allow an unoccupied job to be listed again after the listing expires
    public function listings()
    {
    	return $this->hasMany('App\Models\JobVacancy');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}
