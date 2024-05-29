<?php

declare(strict_types=1);

namespace App\Casts;

use Carbon\CarbonPeriod;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Period implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): CarbonPeriod
    {
        $period = new CarbonPeriod($attributes['from'], $attributes['to']);

        return $period->setTimezone($attributes['timezone']);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (! $value instanceof CarbonPeriod) {
            throw new InvalidArgumentException('The given value is not an CarbonPeriod instance.');
        }

        return [
            'from' => $value->getStartDate()->utc(),
            'to' => $value->getEndDate()->utc(),
            'timezone' => $value->getEndDate()->getTimezone(),
        ];
    }
}
