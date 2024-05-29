<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\AlreadyAppliedEvent;
use App\Exceptions\AlreadyDeclinedEvent;
use App\Exceptions\PaymentRequiredEvent;
use App\Models\Event;
use App\Models\User;
use App\UseCases\ApplyDatingEventCommand;
use App\UseCases\ApplyDatingEventUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApplyDatingEventUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pay_and_apply_for_dating()
    {
        $event = Event::factory()->create();
        $applier = User::factory()->create();
        $applier->payments()->attach($event, ['paid_at' => Date::now()]);
        $command = new ApplyDatingEventCommand;
        $command->event = $event;

        $usecase = new ApplyDatingEventUseCase;
        $usecase->execute($applier, $command);

        $this->assertTrue($applier->attendance->contains($event));
    }

    #[Test]
    public function given_unpaid_event_throws_exception()
    {
        $event = Event::factory()->create();
        $applier = User::factory()->create();
        $command = new ApplyDatingEventCommand;
        $command->event = $event;

        $this->expectException(PaymentRequiredEvent::class);

        $usecase = new ApplyDatingEventUseCase;
        $usecase->execute($applier, $command);
    }

    #[Test]
    public function given_already_applied_event_throws_exception()
    {
        $event = Event::factory()->create();
        $applier = User::factory()->create();
        $applier->payments()->attach($event, ['paid_at' => Date::now()]);
        $command = new ApplyDatingEventCommand;
        $command->event = $event;
        $usecase = new ApplyDatingEventUseCase;
        $usecase->execute($applier, $command);

        $this->expectException(AlreadyAppliedEvent::class);

        $usecase = new ApplyDatingEventUseCase;
        $usecase->execute($applier, $command);
    }

    #[Test]
    public function given_already_declined_event_throws_exception()
    {
        $event = Event::factory()->create();
        $applier = User::factory()->create();
        $applier->attendance()->attach($event, ['declined_at' => Date::now()]);

        $this->expectException(AlreadyDeclinedEvent::class);

        $command = new ApplyDatingEventCommand;
        $command->event = $event;
        $usecase = new ApplyDatingEventUseCase;
        $usecase->execute($applier, $command);
    }
}
