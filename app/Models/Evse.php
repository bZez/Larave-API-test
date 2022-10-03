<?php

namespace App\Models;

use App\Builders\EvseBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * @property int      $id
 * @property int      $operator_id
 * @property string   $ref
 * @property string   $address
 * @property string   $created_at
 * @property string   $updated_at
 * @property Cdr[]    $cdrs
 * @property Operator $operator
 */
class Evse extends Model
{
    protected $fillable = ['operator_id', 'ref', 'address', 'created_at', 'updated_at'];

    /**
     * @var array<string>
     */
    protected $with = ['operator'];

    public function cdrs(): HasMany
    {
        return $this->hasMany('App\Models\Cdr');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo('App\Models\Operator');
    }

    /**
     * @param Builder $query
     */
    public function newEloquentBuilder($query): EvseBuilder
    {
        return new EvseBuilder($query);
    }
}
