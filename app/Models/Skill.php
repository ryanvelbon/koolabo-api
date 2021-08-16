<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
	// This inverse function let's us find all the users who have a certain skill by simply typing: $skill->users()
    public function users(){
		return $this->belongsToMany('App\Models\User');
	}

	public function listings(){
		return $this->belongsToMany('App\Models\Listing');
	}
}
