<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    use HasFactory;

    const ROLE_OWNER = 1;
    const ROLE_ADMIN = 2;
    const ROLE_USER = 3;

    protected $fillable = [
        'user_id',
        'chat_id',
        'role'
    ];
}
