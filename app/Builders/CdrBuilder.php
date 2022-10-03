<?php

namespace App\Builders;

use App\Models\Cdr;
use App\Models\Operator;
use Illuminate\Database\Eloquent\Builder;

class CdrBuilder extends Builder
{
    /**
     * @return $this
     */
    public function whereRef(string $ref): self
    {
        return $this->where('ref', '=', $ref);
    }

     public function whereOperatorIs(Operator $operator): Cdr|Builder
     {
         return Cdr::query()->whereHas('evse', function ($query) use ($operator) {
             $query->where('operator_id', '=', $operator->id);
         });
     }
}
