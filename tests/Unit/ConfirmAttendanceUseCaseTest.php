<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\AlreadyDeclinedEvent;
use App\Exceptions\NotAppliedEvent;
use App\Models\Event;
use App\Models\User;
use App\UseCases\ConfirmAttendanceUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConfirmAttendanceUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function confirm_attendance()
    {
        $event = Event::factory()->create();
        $attendee = User::factory()->create();
        $attendee->attendance()->attach($event);

        $usecase = new ConfirmAttendanceUseCase;
        $usecase->execute($attendee, $event);

        $this->assertEquals(2, $event->confirmed_participant_count);
        $event->attendance->each(fn ($event) => $this->assertNotNull($event->pivot->confirmed_at));
    }

    #[Test]
    public function given_not_applied_event_throw_exception()
    {
        $event = Event::factory()->create();
        $attendee = User::factory()->create();

        $this->expectException(NotAppliedEvent::class);

        $usecase = new ConfirmAttendanceUseCase;
        $usecase->execute($attendee, $event);
    }

    #[Test]
    public function given_declined_event_throw_exception()
    {
        $event = Event::factory()->create();
        $attendee = User::factory()->create();
        $attendee->attendance()->attach($event, ['declined_at' => Date::now()]);

        $this->expectException(AlreadyDeclinedEvent::class);

        $usecase = new ConfirmAttendanceUseCase;
        $usecase->execute($attendee, $event);
    }
}
