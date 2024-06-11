<?php

namespace App\Aggregates;

use App\Events\ApplicantHasNotPaid;
use App\Events\ApplicantWasAccepted;
use App\Events\EventPaymentReceived;
use App\Exceptions\NotAppliedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class Event extends AggregateRoot
{
    public Date $date;
    public User $organiser;
    public ?User $applicant = null;
    public bool $isPaid = false;

    public function accept(User $applicant): void
    {
        if (! $this->wasPaid($applicant)) {
            $this->recordThat(new ApplicantHasNotPaid($applicant));

            throw NotAppliedEvent::create($applicant, $this);
        }

        $this->recordThat(new ApplicantWasAccepted($applicant));
    }

    public function wasPaid(User $applicant): bool
    {
        return $this->applicant?->is($applicant) && $this->isPaid;
    }

    public function applyEventPaymentReceived(EventPaymentReceived $event): void
    {
        $this->isPaid = true;
        $this->applicant = $event->profile;
    }
}
