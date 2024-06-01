<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Models\Event;
use App\Models\User;
use RuntimeException;

class UnauthorizedEvent extends RuntimeException
{
    public static function notAllowedToAcceptApplicant(User $applicant, Event $event): self
    {
        return new self('You are not authorised to accept applicant for this event');
    }
}
