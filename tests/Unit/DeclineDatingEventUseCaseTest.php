<?php

namespace Tests\Unit;

use App\Exceptions\AlreadyDeclinedEvent;
use App\Models\Event;
use App\Models\User;
use App\UseCases\DeclineDatingEventUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeclineDatingEventUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function decline_attendance()
    {
        $event = Event::factory()->create();
        $attendee = User::factory()->create();

        $usecase = new DeclineDatingEventUseCase;
        $usecase->execute($attendee, $event);

        $this->assertCount(1, $event->attendance);
        $event->attendance->each(fn ($event) => $this->assertNotNull($event->pivot->declined_at));
    }

    #[Test]
    public function given_already_applied_event_declines_attendance()
    {
        $event = Event::factory()->create();
        $attendee = User::factory()->create();
        $attendee->attendance()->attach($event);

        $usecase = new DeclineDatingEventUseCase;
        $usecase->execute($attendee, $event);

        $this->assertCount(1, $event->attendance);
        $event->attendance->each(fn ($event) => $this->assertNotNull($event->pivot->declined_at));
    }

    #[Test]
    public function given_already_confirmed_event_declines_attendance_and_decrement_participants()
    {
        $event = Event::factory()->create([
            'confirmed_participant_count' => 2,
        ]);
        $attendee = User::factory()->create();
        $attendee->attendance()->attach($event, ['confirmed_at' => Date::now()]);

        $usecase = new DeclineDatingEventUseCase;
        $usecase->execute($attendee, $event);

        $this->assertCount(1, $event->attendance);
        $this->assertEquals(1, $event->confirmed_participant_count);
        $event->attendance->each(fn ($event) => $this->assertNotNull($event->pivot->declined_at));
    }

    #[Test]
    public function given_already_declined_event_throws_exception()
    {
        $event = Event::factory()->create();
        $attendee = User::factory()->create();
        $usecase = new DeclineDatingEventUseCase;
        $usecase->execute($attendee, $event);

        $this->expectException(AlreadyDeclinedEvent::class);

        $usecase = new DeclineDatingEventUseCase;
        $usecase->execute($attendee, $event);
    }
}
