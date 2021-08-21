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

    public function listings()
    {
    	return $this->hasMany('App\Models\JobVacancy');
    }
}
