<?php

namespace App;

use App\Aggregates\Event;
use Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository;

interface EventRepository
{
    public function persist(Event $event): void;
}
