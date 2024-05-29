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

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'organiser_id');
    }

    public function attendance(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'attendance')
            ->withPivot('confirmed_at');
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'payments')
            ->withPivot('paid_at');
    }
}
