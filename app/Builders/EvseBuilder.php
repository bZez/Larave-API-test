<?php

namespace App\Builders;

use App\Models\Operator;
use Illuminate\Database\Eloquent\Builder;

class EvseBuilder extends Builder
{
    /**
     * @return $this
     */
    public function whereRef(string $ref): self
    {
        return $this->where('ref', '=', $ref);
    }

    public function whereOperatorIs(Operator $operator): self
    {
        return $this->where('operator_id', '=', $operator->id);
    }
}
