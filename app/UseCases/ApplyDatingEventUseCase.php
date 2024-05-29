<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Exceptions\AlreadyAppliedEvent;
use App\Exceptions\AlreadyDeclinedEvent;
use App\Exceptions\PaymentRequiredEvent;
use App\Models\User;

class ApplyDatingEventUseCase
{
    public function execute(User $applier, ApplyDatingEventCommand $command): void
    {
        if ($applier->hasDeclined($command->event)) {
            throw AlreadyDeclinedEvent::create($command->event);
        }

        if ($applier->hasApplied($command->event)) {
            throw AlreadyAppliedEvent::create($command->event);
        }

        if ($applier->hasNotPaid($command->event)) {
            throw PaymentRequiredEvent::create($command->event);
        }

        $applier->attendance()->attach($command->event);

        // send a message for the plan owner
    }
}
