<?php

namespace App\Events;

use App\Aggregates\Event;
use App\Models\User;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class EventPaymentReceived extends ShouldBeStored
{
    public function __construct(public Event $event, public User $profile)
    {
    }
}
