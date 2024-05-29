<?php

declare(strict_types=1);

namespace App\UseCases;

use Carbon\CarbonPeriod;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ScheduleDatingEventCommand
{
    public Point $location;
    public string $title;
    public string $notes;
    public CarbonPeriod $period;
}
