<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\User;

class ApplyDatingEventUseCase
{
    public function execute(User $applier, ApplyDatingEventCommand $command): void
    {
        $applier->attendance()->attach($command->event);

        // send a message for the plan owner
    }
}
