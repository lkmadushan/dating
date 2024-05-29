<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Exceptions\ConfirmAttendanceException;
use App\Models\Event;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ConfirmAttendanceUseCase
{
    public function execute(User $attendee, Event $event): void
    {
        if (! $attendee->attendance()->where('event_id', $event->getKey())->exists()) {
            throw ConfirmAttendanceException::notApplied();
        }

        try {
            DB::beginTransaction();

            $attendee->attendance()->updateExistingPivot($event->getKey(), ['confirmed_at' => Date::now()]);
            $event->increment('confirmed_participant_count');

            DB::commit();
        } catch (Exception) {
            DB::rollBack();
        }
    }
}
