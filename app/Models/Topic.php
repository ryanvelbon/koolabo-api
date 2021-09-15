<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
	public function users(){
		return $this->belongsToMany(User::class, 'user_topics');
	}
}
