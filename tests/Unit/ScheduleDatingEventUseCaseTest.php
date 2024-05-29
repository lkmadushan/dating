<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\UseCases\ScheduleDatingEventCommand;
use App\UseCases\ScheduleDatingEventUseCase;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MatanYadaev\EloquentSpatial\Objects\Point;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ScheduleDatingEventUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function given_schedule_details_creates_dating_event()
    {
        $user = User::factory()->create();
        $command = new ScheduleDatingEventCommand;
        $command->location = new Point(1.0, 1.0);
        $command->title = 'Title here';
        $command->notes = 'Some notes here';
        $command->period = CarbonPeriod::create('2021-01-01 09:00:00', '2021-01-01 17:30:00')
            ->shiftTimezone('Asia/Colombo');

        $usecase = new ScheduleDatingEventUseCase;
        $usecase->execute($user, $command);

        /** @var Event $event */
        $event = $user->events->first();
        $this->assertEquals($user->getKey(), $event->organiser_id);
        $this->assertEquals('Title here', $event->title);
        $this->assertEquals('Some notes here', $event->notes);
        $this->assertEquals('2021-01-01 09:00:00', $event->period->getStartDate());
        $this->assertEquals('2021-01-01 17:30:00', $event->period->getEndDate());
        $this->assertEquals('Asia/Colombo', $event->period->getEndDate()->getTimezone());
        $this->assertEquals(1, $event->confirmed_participant_count);
        $this->assertTrue($user->attendance->contains($event));
        $this->assertCount(1, $user->events);
        $this->assertCount(1, $user->attendance);
    }
}
