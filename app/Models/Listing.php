<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'craft_id', 'location_id',
    ];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function craft()
    {
        return $this->hasOne('App\Models\Craft');
    }

    public function location()
    {
        return $this->hasOne('App\Models\Location');
    }

    public function topics(){
        return $this->belongsToMany('App\Models\Topic');
    }

    public function skills(){
        return $this->belongsToMany('App\Models\Skill');
    }
}
