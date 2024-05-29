<?php

namespace App\Exceptions;

use App\Models\Event;
use RuntimeException;

class AlreadyAppliedForEvent extends RuntimeException
{
    public static function create(Event $event): self
    {
        return new self('You are already applied this event');
    }
}
