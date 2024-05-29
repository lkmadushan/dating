<?php

namespace App\Exceptions;

use Exception;

class ConfirmAttendanceException extends Exception
{
    public static function notApplied(): static
    {
        return new static('User has not applied for this event');
    }
}
