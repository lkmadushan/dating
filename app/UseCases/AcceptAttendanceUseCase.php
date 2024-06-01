<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Exceptions\NotAppliedEvent;
use App\Exceptions\UnauthorizedEvent;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class AcceptAttendanceUseCase
{
    public function execute(User $organiser, AcceptAttendanceCommand $command): void
    {
        if ($command->event->organiser->isNot($organiser)) {
            throw UnauthorizedEvent::notAllowedToAcceptApplicant($command->applicant, $command->event);
        }

        if ($command->applicant->hasNotPaid($command->event)) {
            throw NotAppliedEvent::create($command->applicant, $command->event);
        }

        try {
            DB::beginTransaction();

            $command->event
                ->attendance()
                ->syncWithoutDetaching([$command->applicant->getKey() => ['accepted_at' => Date::now()]]);
            $command->event->increment('confirmed_participant_count');

            DB::commit();
        } catch (Exception) {
            DB::rollBack();
        }
    }
}
