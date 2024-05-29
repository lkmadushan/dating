<?php

namespace Tests\Unit;

use App\Exceptions\EventAlreadyDeclined;
use App\Models\Event;
use App\Models\User;
use App\UseCases\DeclineDatingEventUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->assertTrue($attendee->attendance()->wherePivotNotNull('declined_at')->get()->contains($event));
    }

    #[Test]
    public function given_already_declined_event_throws_exception()
    {
        $event = Event::factory()->create();
        $attendee = User::factory()->create();
        $usecase = new DeclineDatingEventUseCase;
        $usecase->execute($attendee, $event);

        $this->expectException(EventAlreadyDeclined::class);

        $usecase = new DeclineDatingEventUseCase;
        $usecase->execute($attendee, $event);
    }
}
