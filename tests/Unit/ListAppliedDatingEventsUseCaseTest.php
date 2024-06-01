<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\UseCases\ListAppliedDatingEventsUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListAppliedDatingEventsUseCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function list_applied_dating_events()
    {
        $event = Event::factory()->create();
        $applicant = User::factory()->create();
        $applicant->payments()->attach($event, ['paid_at' => Date::now()]);

        $usecase = new ListAppliedDatingEventsUseCase;
        $events = $usecase->execute($applicant);

        $this->assertTrue($events->contains($event));
    }
}
