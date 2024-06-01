<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\Event;
use App\Models\User;

class AcceptAttendanceCommand
{
    public Event $event;
    public User $applicant;
}
