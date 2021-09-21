<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    const TYPE_PRIVATE = 1; // 1-to-1 chat
    const TYPE_GROUP = 2; // group chat
    const TYPE_PROJECT = 3; // group chat corresponding to project
    const TYPE_FORUM = 4; // public group chat

    protected $fillable = [
        'type'
    ];

    // *REVISE* this function should only be available for TYPE_PRIVATE chats
    public function owner(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'owner_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'chat_participants')->withPivot('role');
    }

    public function messages(): HasMany
    {
        return $this->HasMany(Message::class);
    }
}
