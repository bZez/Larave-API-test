<?php

namespace App\Casts;

use DateTime;
use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Util\Type;

class DateTimeCast implements CastsAttributes
{
    public const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s\Z';

    /**
     * Cast the given value.
     *
     * @param Model       $model
     * @param string      $value
     * @param array<Type> $attributes
     *
     * @throws Exception
     */
    public function get($model, string $key, $value, array $attributes): string
    {
        $date = new DateTime($value);

        return $date->format(self::DATE_TIME_FORMAT);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model       $model
     * @param mixed       $value
     * @param array<Type> $attributes
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        return $value;
    }
}
