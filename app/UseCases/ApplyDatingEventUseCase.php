<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Exceptions\ApplyDatingEventException;
use App\Models\User;

class ApplyDatingEventUseCase
{
    public function execute(User $applier, ApplyDatingEventCommand $command): void
    {
        if ($applier->hasApplied($command->event)) {
            throw ApplyDatingEventException::alreadyApplied();
        }

        if ($applier->hasNotPaid($command->event)) {
            throw ApplyDatingEventException::paymentRequired();
        }

        $applier->attendance()->attach($command->event);

        // send a message for the plan owner
    }
}
