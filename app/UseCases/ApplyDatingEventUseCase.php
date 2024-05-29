<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Exceptions\ApplyDatingEventException;
use App\Models\User;

class ApplyDatingEventUseCase
{
    public function execute(User $applier, ApplyDatingEventCommand $command): void
    {
        if ($applier->attendance()->where('event_id', $command->event->getKey())->exists()) {
            throw ApplyDatingEventException::alreadyApplied();
        }

        if (! $applier->payments()->where('event_id', $command->event->getKey())->exists()) {
            throw ApplyDatingEventException::paymentRequired();
        }

        $applier->attendance()->attach($command->event);

        // send a message for the plan owner
    }
}
