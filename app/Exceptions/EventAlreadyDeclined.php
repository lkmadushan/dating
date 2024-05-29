<?php

namespace App\Exceptions;

use App\Models\Event;
use RuntimeException;

class EventAlreadyDeclined extends RuntimeException
{
    public static function create(Event $event): self
    {
        return new self('You have already declined this event');
    }
}
