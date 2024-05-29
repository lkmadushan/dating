<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\UseCases\ApplyDatingEventCommand;
use App\UseCases\ApplyDatingEventUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApplyDatingEventUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function apply_for_dating()
    {
        $event = Event::factory()->create();
        $applier = User::factory()->create();
        $command = new ApplyDatingEventCommand;
        $command->event = $event;

        $usecase = new ApplyDatingEventUseCase;
        $usecase->execute($applier, $command);

        $this->assertTrue($applier->attendance->contains($event));
    }
}
