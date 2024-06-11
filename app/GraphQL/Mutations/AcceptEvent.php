<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Aggregates\Event;
use App\Models\User;
use App\UseCases\AcceptAttendanceCommand;
use App\UseCases\AcceptAttendanceUseCase;
use Exception;

final readonly class AcceptEvent
{
    public function __construct(protected AcceptAttendanceUseCase $usecase)
    {
    }

    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        try {
            $command = new AcceptAttendanceCommand;
            /** @var User $applicant */
            $applicant = User::query()->findOrFail($args['applicant_id']);
            $command->event = Event::retrieve($args['event_id']);
            $command->applicant = $applicant;

            /** @var User $organiser */
            $organiser = auth()->user();
            $this->usecase->execute($organiser, $command);
        } catch (Exception $e) {
            // Send error response
        }
    }
}
