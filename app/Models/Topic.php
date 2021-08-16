<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
	public function users(){
		return $this->belongsToMany('App\Models\User');
	}

	public function listings(){
		return $this->belongsToMany('App\Models\Listing');
	}
}
