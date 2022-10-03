<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Util\Type;

class EnergyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model       $model
     * @param mixed       $value
     * @param array<Type> $attributes
     */
    public function get($model, string $key, $value, array $attributes): float
    {
        return (float) number_format($value / 1000, 3, '.', '');
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model       $model
     * @param float       $value
     * @param array<Type> $attributes
     */
    public function set($model, string $key, $value, array $attributes): int
    {
        return (int) ((float) $value * 1000);
    }
}
