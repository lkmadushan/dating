<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\ConfirmAttendanceException;
use App\Models\Event;
use App\Models\User;
use App\UseCases\ConfirmAttendanceUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $this->assertTrue($attendee->attendance()->wherePivotNotNull('confirmed_at')->get()->contains($event));
    }

    #[Test]
    public function given_not_applied_event_throw_exception()
    {
        $event = Event::factory()->create();
        $attendee = User::factory()->create();

        $this->expectException(ConfirmAttendanceException::class);

        $usecase = new ConfirmAttendanceUseCase;
        $usecase->execute($attendee, $event);
    }
}
