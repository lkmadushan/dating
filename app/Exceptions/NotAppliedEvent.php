<?php

namespace App\Exceptions;

use App\Models\Event;
use App\Models\User;
use RuntimeException;

class NotAppliedEvent extends RuntimeException
{
    public static function create(User $applicant, Event $event): self
    {
        return new self('You have not applied for this event');
    }
}
