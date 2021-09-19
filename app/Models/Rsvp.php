<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rsvp extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_SENT = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_ACCEPTED = 3;
    const STATUS_DECLINED = 4;
    const STATUS_ARRIVED = 5;

    protected $casts = [
        'status' => 'integer'
    ];
}
