<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Meetup extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
    ];

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'lat',
        'lng',
        'address_line1',
        'start_time',
        'end_time'
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'resource');
    }

    public function rsvps(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rsvps')->withPivot('status');
    }

    public function scopeNearestTo(Builder $builder, $lat, $lng)
    {
        return $builder
            ->select()
            ->orderByRaw(
                'SQRT(POW(69.1 * (lat - ?), 2) + POW(69.1 * (? - lng) * COS(lat / 57.3), 2))',
                [$lat, $lng]
            );
    }
}
