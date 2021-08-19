<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'is_offering',
        'craft_id',
        'city_id',
    ];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function craft()
    {
        return $this->hasOne('App\Models\Craft');
    }

    public function city()
    {
        return $this->hasOne('App\Models\City');
    }

    public function topics(){
        return $this->belongsToMany('App\Models\Topic');
    }

    public function skills(){
        return $this->belongsToMany('App\Models\Skill');
    }
}
