<?php

namespace App\Exceptions;

use DomainException;

class ConfirmAttendanceException extends DomainException
{
    public static function notApplied(): static
    {
        return new static('User has not applied for this event');
    }
}
