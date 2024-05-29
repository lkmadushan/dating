<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'organiser_id' => User::factory(),
            'title' => $this->faker->sentence,
            'notes' => $this->faker->paragraph,
            'location' => new Point($this->faker->latitude, $this->faker->longitude),
            'confirmed_participant_count' => 1,
            'period' => CarbonPeriod::create(
                $this->faker->dateTimeBetween('-1 week', '+1 week'),
                $this->faker->dateTimeBetween('+1 week', '+2 week')
            ),
        ];
    }
}
