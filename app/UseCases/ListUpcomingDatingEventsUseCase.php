<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ListUpcomingDatingEventsUseCase
{
    public function execute(User $viewer, ListUpcomingDatingEventsCommand $command): Collection
    {
        return Event::query()
            ->when($viewer->preferred_distance_only, function ($query) use ($viewer, $command) {
                $query->whereDistance('location', $command->location, '<=', $viewer->preferred_distance);
            })
            ->orderByDistance('location', $command->location)
            ->get();
    }
}
