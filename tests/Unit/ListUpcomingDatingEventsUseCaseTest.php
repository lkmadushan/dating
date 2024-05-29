<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\UseCases\ListUpcomingDatingEventsCommand;
use App\UseCases\ListUpcomingDatingEventsUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListUpcomingDatingEventsUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function list_dating_events()
    {
        $viewer = User::factory()->create();
        $viewer->preferred_distance = 120_000;
        $viewer->preferred_distance_only = true;
        $event1 = Event::factory()->create([
            'location' => new Point(6.9271, 79.8612, Srid::WGS84->value) // Colombo
        ]);
        $event2 = Event::factory()->create([
            'location' => new Point(7.2906, 80.6337, Srid::WGS84->value) // Kandy
        ]);
        $event3 = Event::factory()->create([
            'location' => new Point(7.0840, 80.0098, Srid::WGS84->value) // Gampaha
        ]);
        $event4 = Event::factory()->create([
            'location' => new Point(8.5874, 81.2152, Srid::WGS84->value) // Trincomalee
        ]);

        $usecase = new ListUpcomingDatingEventsUseCase;
        $command = new ListUpcomingDatingEventsCommand;
        $command->location = new Point(6.9271, 79.8612, Srid::WGS84->value); // Colombo
        $events = $usecase->execute($viewer, $command);

        $this->assertSame([
            $event1->getKey(),
            $event3->getKey(),
            $event2->getKey()
        ], $events->modelKeys());
        $this->assertFalse($events->contains($event4));
    }
}
