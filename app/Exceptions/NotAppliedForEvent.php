<?php

namespace App\Exceptions;

use App\Models\Event;
use RuntimeException;

class NotAppliedForEvent extends RuntimeException
{
    public static function create(Event $event): self
    {
        return new self('You have not applied for this event');
    }
}
