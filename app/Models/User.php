<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @property Collection $events
 * @property Collection $attendance
 * @property int $preferred_distance
 * @property bool $preferred_distance_only
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasCancelled(Event $event): bool
    {
        return $this->attendance()
            ->where('event_id', $event->getKey())
            ->wherePivotNotNull('cancelled_at')
            ->exists();
    }

    public function hasNotPaid(Event $event): bool
    {
        return ! $this->hasPaid($event);
    }

    public function hasPaid(Event $event): bool
    {
        return $this->payments()->where('event_id', $event->getKey())->exists();
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'organiser_id');
    }

    public function attendance(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'attendance')
            ->withPivot(['accepted_at', 'cancelled_at']);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'payments')
            ->withPivot('paid_at');
    }
}
