<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cdr>
 */
class CdrFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'ref' => Str::random(36),
            'start_datetime' => fake()->dateTime(),
            'end_datetime' => fake()->dateTime(),
            'total_energy' => fake()->randomDigitNotZero(),
            'total_cost' => fake()->randomDigitNotZero(),
            'evse_id' => EvseFactory::new()->create()->id,
        ];
    }
}
