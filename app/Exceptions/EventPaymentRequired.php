<?php

namespace App\Exceptions;

use App\Models\Event;
use RuntimeException;

class EventPaymentRequired extends RuntimeException
{
    public static function create(Event $event): self
    {
        return new self('You must pay the onetime fee to apply for the event');
    }
}
