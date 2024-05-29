<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ListAppliedDatingEventsUseCase
{
    public function execute(User $attendee): Collection
    {
        return Event::query()
            ->whereIn(
                'id',
                DB::table('payments')
                    ->select('event_id')
                    ->where('user_id', $attendee->getKey())
            )
            ->get();
    }
}
