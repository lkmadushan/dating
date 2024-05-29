<?php

namespace App\UseCases;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use App\Exceptions\DeclineDatingEventException;

class DeclineDatingEventUseCase
{
    public function execute(User $decliner, Event $event): void
    {
        if ($decliner->hasDeclined($event)) {
            throw DeclineDatingEventException::alreadyDeclined();
        }

        $decliner->attendance()->attach($event->getKey(), ['declined_at' => Date::now()]);
    }
}