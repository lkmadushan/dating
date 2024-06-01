<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\Event;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ScheduleDatingEventUseCase
{
    public function execute(User $user, ScheduleDatingEventCommand $command): void
    {
        try {
            DB::beginTransaction();

            $event = new Event;
            $event->location = $command->location;
            $event->title = $command->title;
            $event->notes = $command->notes;
            $event->period = $command->period;
            $event->confirmed_participant_count = 1;

            $user->events()->save($event);
            $user->attendance()->attach($event, ['accepted_at' => Date::now()]);

            DB::commit();
        } catch (Exception) {
            DB::rollBack();
        }
    }
}
