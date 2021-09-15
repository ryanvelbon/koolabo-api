<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Craft extends Model
{
    public function Jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function Users()
    {
        return $this->belongsToMany(User::class, 'user_crafts');
    }
}
