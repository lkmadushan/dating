<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Aggregates\Event;
use App\Models\User;

class AcceptAttendanceCommand
{
    public Event $event;
    public User $applicant;
}
