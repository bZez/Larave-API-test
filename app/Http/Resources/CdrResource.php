<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nette\Utils\Type;

class CdrResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array<Type>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->ref,
            'evse_uid' => $this->resource->evse->ref,
            'start_datetime' => $this->resource->start_datetime,
            'end_datetime' => $this->resource->end_datetime,
            'total_energy' => $this->resource->total_energy,
            'total_cost' => $this->resource->total_cost,
        ];
    }
}
