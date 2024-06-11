<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Aggregates\Event;
use App\EventRepository;
use App\Events\ApplicantHasNotPaid;
use App\Events\ApplicantWasAccepted;
use App\Events\EventPaymentReceived;
use App\Exceptions\NotAppliedEvent;
use App\Models\User;
use App\UseCases\AcceptAttendanceCommand;
use App\UseCases\AcceptAttendanceUseCase;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AcceptAttendanceUseCaseTest extends TestCase
{
    #[Test]
    public function given_applicant_paid_event_accept_attendance()
    {
        $event = new Event;
        $event->organiser = User::factory()->make();
        $applicant = User::factory()->make();
        $event->applyEventPaymentReceived(new EventPaymentReceived($event, $applicant));

        $usecase = new AcceptAttendanceUseCase($repository = new InMemoryRepository);
        $command = new AcceptAttendanceCommand;
        $command->applicant = $applicant;
        $command->event = $event;
        $usecase->execute($event->organiser, $command);

        $this->assertCount(1, $event->getRecordedEvents());
        $this->assertInstanceOf(ApplicantWasAccepted::class, $event->getRecordedEvents()[0]);
        $this->assertCount(1, $repository->events);
        $this->assertCount(1, $repository->acceptedEvents);
    }

    #[Test]
    public function given_applicant_unpaid_event_throws_exception()
    {
        $event = new Event;
        $event->organiser = User::factory()->make();
        $applicant = User::factory()->make();
        $repository = new InMemoryRepository;

        try {
            $usecase = new AcceptAttendanceUseCase($repository);
            $command = new AcceptAttendanceCommand;
            $command->event = $event;
            $command->applicant = $applicant;
            $usecase->execute($event->organiser, $command);
        } catch (Exception $e) {
            $this->assertInstanceOf(NotAppliedEvent::class, $e);
        }

        $this->assertCount(1, $event->getRecordedEvents());
        $this->assertInstanceOf(ApplicantHasNotPaid::class, $event->getRecordedEvents()[0]);
        $this->assertCount(1, $repository->events);
        $this->assertCount(0, $repository->acceptedEvents);
    }
}

class InMemoryRepository implements EventRepository
{
    public array $events = []; // The event store
    public array $acceptedEvents = []; // A read model

    public function persist(Event $event): void
    {
        $this->events[] = $event;

        if ($event->isPaid) {
            $this->acceptedEvents[] = $event;
        }
    }
}
