<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\Period;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

/**
 * @property Point $location
 * @property string $title
 * @property string $organiser_id;
 * @property string $notes
 * @property CarbonPeriod $period
 * @property Collection $attendance
 * @property User $organiser
 * @property int $confirmed_participant_count
 * @property Carbon $cancelled_at
 */
class Event extends Model
{
    use HasFactory, HasSpatial;

    protected $casts = [
        'period' => Period::class,
        'location' => Point::class,
    ];

    public function organiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    public function attendance(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attendance')
            ->withPivot(['accepted_at', 'cancelled_at']);
    }
}
