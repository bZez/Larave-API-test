<?php

namespace App\Models;

use App\Builders\OperatorBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * @property int    $id
 * @property string $name
 * @property string $access_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Evse[] $evses
 */
class Operator extends Model
{
    protected $fillable = ['name', 'access_token', 'created_at', 'updated_at'];

    /**
     * @var array<string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function evses(): HasMany
    {
        return $this->hasMany('App\Models\Evse');
    }

    /**
     * @param Builder $query
     */
    public function newEloquentBuilder($query): OperatorBuilder
    {
        return new OperatorBuilder($query);
    }
}
