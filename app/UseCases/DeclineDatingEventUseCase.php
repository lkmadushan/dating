<?php

namespace App\UseCases;

use App\Models\Event;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Date;
use App\Exceptions\AlreadyDeclinedEvent;
use Illuminate\Support\Facades\DB;

class DeclineDatingEventUseCase
{
    public function execute(User $user, Event $event): void
    {
        if ($user->hasDeclined($event)) {
            throw AlreadyDeclinedEvent::create($event);
        }

        try {
            DB::beginTransaction();

            if ($user->hasConfirmed($event)) {
                $event->decrement('confirmed_participant_count');
            }

            $user->attendance()->syncWithoutDetaching([$event->getKey() => ['declined_at' => Date::now()]]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
