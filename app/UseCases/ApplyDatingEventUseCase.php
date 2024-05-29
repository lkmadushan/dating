<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Exceptions\AlreadyAppliedForEvent;
use App\Exceptions\EventPaymentRequired;
use App\Models\User;

class ApplyDatingEventUseCase
{
    public function execute(User $applier, ApplyDatingEventCommand $command): void
    {
        if ($applier->hasApplied($command->event)) {
            throw AlreadyAppliedForEvent::create($command->event);
        }

        if ($applier->hasNotPaid($command->event)) {
            throw EventPaymentRequired::create($command->event);
        }

        $applier->attendance()->attach($command->event);

        // send a message for the plan owner
    }
}
