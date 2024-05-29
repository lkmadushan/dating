<?php

namespace App\Exceptions;

use App\Models\Event;
use RuntimeException;

class AlreadyAppliedEvent extends RuntimeException
{
    public static function create(Event $event): self
    {
        return new self('You are already applied this event');
    }
}
