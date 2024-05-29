<?php

declare(strict_types=1);

namespace App\UseCases;

use MatanYadaev\EloquentSpatial\Objects\Point;

class ListUpcomingDatingEventsCommand
{
    public Point $location;
}
