<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\Event;

class ApplyDatingEventCommand
{
    public Event $event;
    public ?string $message;
}
