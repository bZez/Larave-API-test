<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class OperatorBuilder extends Builder
{
    public function whereAccessTokenIs(string $token): self
    {
        return $this->where('access_token', '=', $token);
    }
}
