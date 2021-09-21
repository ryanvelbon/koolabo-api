<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ChatInvite extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_SENT = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_ACCEPTED = 3;
    const STATUS_DECLINED = 4;

    protected $casts = [
        'status' => 'integer'
    ];

    protected $fillable = [
        'chat_id',
        'recipient_id',
        'sender_id'
    ];
}
