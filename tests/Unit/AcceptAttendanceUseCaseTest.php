<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\NotAppliedEvent;
use App\Exceptions\UnauthorizedEvent;
use App\Models\Event;
use App\Models\User;
use App\UseCases\AcceptAttendanceCommand;
use App\UseCases\AcceptAttendanceUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AcceptAttendanceUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function accept_attendance()
    {
        $event = Event::factory()->create();
        $applicant = User::factory()->create();
        $applicant->payments()->attach($event, ['paid_at' => Date::now()]);

        $usecase = new AcceptAttendanceUseCase;
        $command = new AcceptAttendanceCommand;
        $command->event = $event;
        $command->applicant = $applicant;
        $usecase->execute($event->organiser, $command);

        $this->assertEquals(2, $event->confirmed_participant_count);
        $this->assertCount(1, $event->attendance);
        $event->attendance->each(fn ($event) => $this->assertNotNull($event->pivot->accepted_at));
    }

    #[Test]
    public function given_unpaid_event_throws_exception()
    {
        $event = Event::factory()->create();
        $applicant = User::factory()->create();

        $this->expectException(NotAppliedEvent::class);

        $usecase = new AcceptAttendanceUseCase;
        $command = new AcceptAttendanceCommand;
        $command->event = $event;
        $command->applicant = $applicant;
        $usecase->execute($event->organiser, $command);
    }

    #[Test]
    public function given_not_organised_event_throws_exception()
    {
        $event = Event::factory()->create();
        $applicant = User::factory()->create();

        $this->expectException(UnauthorizedEvent::class);

        $usecase = new AcceptAttendanceUseCase;
        $command = new AcceptAttendanceCommand;
        $command->event = $event;
        $command->applicant = $applicant;
        $usecase->execute($applicant, $command);
    }
}
