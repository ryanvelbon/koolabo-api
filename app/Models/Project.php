<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // perhaps 'manager' should be excluded since this should be Auth::user by default.
    protected $fillable = [
    	'title',
    	'description',
    	'manager', // exclude?
    	'type',
    	'projected_timeline',
    	'planned_start_date',
    	'planned_end_date'
    ];
}
