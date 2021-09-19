<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectInvite extends Model
{
    use HasFactory;
    use SoftDeletes;

    const STATUS_SENT = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_ACCEPTED = 3;
    const STATUS_DECLINED = 4;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'project_id',
        'msg',
        'status'
    ];

    protected $casts = [
        'status' => 'integer'
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
