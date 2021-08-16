<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	protected $fillable = [
        'user_id', 'date_of_birth', 'craft_id', 'location_id',
    ];

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function location()
    {
        return $this->hasOne('App\Models\Location');
    }

    public $timestamps = false;
    // public $incrementing = false;
    // protected $primaryKey = 'user_id'; // actually user_id cannot be made PK because it's a FK
}