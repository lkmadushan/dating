<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ListConfirmedDatingEventsUseCase
{
    public function execute(User $viewer): Collection
    {
        return Event::query()
            ->with('attendance')
            ->whereIn(
                'id',
                DB::table('attendance')
                    ->select('event_id')
                    ->where('user_id', $viewer->getKey())
                    ->whereNull('cancelled_at')
                    ->whereNotNull('accepted_at')
            )
            ->where('confirmed_participant_count', '>', 1)
            ->get();
    }
}
