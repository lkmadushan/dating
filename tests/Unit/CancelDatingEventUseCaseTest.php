<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\UseCases\CancelDatingEventUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CancelDatingEventUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function cancel_event_by_organiser()
    {
        $event = Event::factory()->create();

        $usecase = new CancelDatingEventUseCase;
        $usecase->execute($event->organiser, $event);

        $this->assertNotNull($event->cancelled_at);
    }

    #[Test]
    public function cancel_event_by_applicant()
    {
        $event = Event::factory()->create();
        $applicant = User::factory()->create();
        $event->attendance()->attach($applicant);

        $usecase = new CancelDatingEventUseCase;
        $usecase->execute($applicant, $event);

        $event->attendance->each(fn ($event) => $this->assertNotNull($event->pivot->cancelled_at));
    }
}
