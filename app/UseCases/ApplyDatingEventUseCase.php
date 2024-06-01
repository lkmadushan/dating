<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Exceptions\PaymentRequiredEvent;
use App\Models\User;

class ApplyDatingEventUseCase
{
    public function execute(User $applicant, ApplyDatingEventCommand $command): void
    {
        if ($applicant->hasNotPaid($command->event)) {
            throw PaymentRequiredEvent::create($command->event);
        }

        $command->event->attendance()->syncWithoutDetaching($applicant);

        // send a message for the plan owner
    }
}
