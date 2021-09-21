<?php

namespace App\Models;

// use Illuminate\Support\Facades\DB;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followings', 'followee_id', 'follower_id');
    }

    public function followees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followings', 'follower_id', 'followee_id');
    }

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'chat_participants');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'resource');
    }

    public function projectInvites(): HasMany
    {
        return $this->hasMany(ProjectInvite::class, 'recipient_id');
    }

    public function projectsLiked(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_likes')->withTimestamps();
    }

    public function getNProjectsLikedAttribute()
    {
        return $this->projectsLiked->count();
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'user_topics');
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'user_languages');
    }

    public function crafts(): BelongsToMany
    {
        return $this->belongsToMany(Craft::class, 'user_crafts');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')->withPivot('level');
    }

    public function rsvps(): BelongsToMany
    {
        return $this->belongsToMany(Meetup::class, 'rsvps')->withPivot('status');
    }
}
