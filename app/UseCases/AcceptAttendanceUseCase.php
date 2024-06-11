<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\User;
use App\EventRepository;
use App\Exceptions\UnauthorizedEvent;

class AcceptAttendanceUseCase
{
    public function __construct(protected EventRepository $repository)
    {
    }

    public function execute(User $organiser, AcceptAttendanceCommand $command): void
    {
        // Application validates the organiser
        if ($command->event->organiser->isNot($organiser)) {
            throw UnauthorizedEvent::notAllowedToAcceptApplicant($command->applicant, $command->event);
        }

        try {
            // All application independent business decisions are made here
            $command->event->accept($command->applicant);
        } finally {
            // Persist the event
            $this->repository->persist($command->event);
        }
    }
}
