<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evse>
 */
class EvseFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'ref' => Str::random(36),
            'address' => fake()->address(),
            'operator_id' => OperatorFactory::new()->create()->id,
        ];
    }
}
