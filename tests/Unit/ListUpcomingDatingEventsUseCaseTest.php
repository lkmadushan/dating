<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\UseCases\ListUpcomingDatingEventsCommand;
use App\UseCases\ListUpcomingDatingEventsUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListUpcomingDatingEventsUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function list_dating_events()
    {
        $viewer = User::factory()->create();
        $viewer->preferred_distance = 100;
        $viewer->preferred_distance_only = true;
        $events = Event::factory(5)->create();

        $usecase = new ListUpcomingDatingEventsUseCase;
        $command = new ListUpcomingDatingEventsCommand;
        $command->location = $events->first()->location;

        $events = $usecase->execute($viewer, $command);

//        dd($events->toQuery()->withDistance('location', $command->location)->get()->toArray());
    }
}
