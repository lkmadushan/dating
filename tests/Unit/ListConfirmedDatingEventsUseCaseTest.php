<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\UseCases\ListConfirmedDatingEventsUseCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListConfirmedDatingEventsUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function list_confirmed_dating_events()
    {
        $organiser1 = User::factory()->create();
        $organiser2 = User::factory()->create();
        $organisers = Collection::wrap([$organiser1, $organiser2]);
        $event1 = Event::factory()->create([
            'organiser_id' => $organiser1->id,
            'confirmed_participant_count' => 2,
        ]);
        $event1->attendance()->attach($organisers->modelKeys(), ['confirmed_at' => Date::now()]);
        $event2 = Event::factory()->create([
            'organiser_id' => $organiser2->id,
            'confirmed_participant_count' => 2,
        ]);
        $event2->attendance()->attach($organisers->modelKeys(), ['confirmed_at' => Date::now()]);
        $event3 = Event::factory()->create([
            'organiser_id' => $organiser1->id,
            'confirmed_participant_count' => 1
        ]);
        $event3->attendance()->attach($organiser1, ['confirmed_at' => Date::now()]);
        $event4 = Event::factory()->create(['confirmed_participant_count' => 2]);

        $useCase = new ListConfirmedDatingEventsUseCase;
        $events = $useCase->execute($organiser1);

        $this->assertCount(2, $events);
        $this->assertTrue($events->contains($event1));
        $this->assertTrue($events->contains($event2));
        $this->assertFalse($events->contains($event3));
        $this->assertFalse($events->contains($event4));
    }
}
