<?php

namespace App\Models;

use App\Builders\CdrBuilder;
use App\Casts\CostCast;
use App\Casts\DateTimeCast;
use App\Casts\EnergyCast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property int    $id
 * @property int    $evse_id
 * @property string $ref
 * @property Carbon $start_datetime
 * @property Carbon $end_datetime
 * @property int    $total_energy
 * @property int    $total_cost
 * @property string $created_at
 * @property string $updated_at
 * @property Evse   $evse
 */
class Cdr extends Model
{
    protected $fillable = ['evse_id', 'ref', 'start_datetime', 'end_datetime', 'total_energy', 'total_cost', 'created_at', 'updated_at'];

    /** @var array<string> */
    protected $with = ['evse'];

    /** @var array<string> */
    protected $casts = [
        'start_datetime' => DateTimeCast::class,
        'end_datetime' => DateTimeCast::class,
        'total_energy' => EnergyCast::class,
        'total_cost' => CostCast::class,
    ];

    public function evse(): BelongsTo
    {
        return $this->belongsTo('App\Models\Evse');
    }

    /**
     * @param Builder $query
     */
    public function newEloquentBuilder($query): CdrBuilder
    {
        return new CdrBuilder($query);
    }
}
