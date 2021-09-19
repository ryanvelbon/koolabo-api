<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/*
 * A user should be able to commend on the following Models:
 *   Comment (reply)
 *   Meetup
 *   Post (user post, project post)
 *   Image
 *   PortfolioItem
 */

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'body'
    ];

    public function resource(): MorphTo
    {
        return $this->morphTo();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function replies(): MorphMany
    {
        return $this->morphMany(Comment::class, 'resource');
    }
}
