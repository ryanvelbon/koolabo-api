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
    	return $this->hasMany(JobVacancy::class);
    }

    public function craft()
    {
        return $this->belongsTo(Craft::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
