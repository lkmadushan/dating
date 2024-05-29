<?php

namespace App\Exceptions;

use DomainException;

class DeclineDatingEventException extends DomainException
{
    public static function alreadyDeclined(): self
    {
        return new self('You have already declined this event.');
    }
}
