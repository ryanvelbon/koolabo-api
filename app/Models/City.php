<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function jobVacancies()
    {
        return $this->hasMany('App\Models\JobVacancy');
    }
}
