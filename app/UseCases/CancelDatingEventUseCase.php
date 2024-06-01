<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class CancelDatingEventUseCase
{
    public function execute(User $applicant, Event $event): void
    {
        if ($event->organiser->is($applicant)) {
            $event->cancelled_at = Date::now();
            $event->save();
        }

        $event->attendance()->syncWithoutDetaching([$applicant->getKey() => ['cancelled_at' => Date::now()]]);
    }
}
