<?php

namespace App\Exceptions;

use DomainException;

class ApplyDatingEventException extends DomainException
{
    public static function alreadyApplied(): self
    {
        return new self('You are already applied this event');
    }

    public static function paymentRequired(): self
    {
        return new self('You must pay the onetime fee to apply for the event');
    }
}
