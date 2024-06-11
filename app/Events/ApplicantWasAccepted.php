<?php

namespace App\Events;

use App\Models\User;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ApplicantWasAccepted extends ShouldBeStored
{
    public function __construct(User $applicant)
    {
    }
}
