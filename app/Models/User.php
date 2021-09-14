<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Project;
// use App\Models\ProjectLike;
use App\Models\Language;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\UserProfile');
    }

    public function interests(){
        return $this->belongsToMany('App\Models\Topic');
    }

    public function projectsLiked()
    {
        return $this->belongsToMany(Project::class, 'project_likes')->withTimestamps();
    }

    public function getNProjectsLikedAttribute()
    {
        return $this->projectsLiked->count();
    }

    public function projectLikes()
    {
        return $this->hasMany(ProjectLike::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'user_languages');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')->withPivot('level');
    }
}
