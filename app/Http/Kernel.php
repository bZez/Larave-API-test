<?php

namespace App\Http;

use App\Http\Middleware\EnsureTokenExists;
use App\Http\Middleware\LogActivity;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /** @var array<int, class-string|string> */
    protected $middleware = [
        EnsureTokenExists::class,
        LogActivity::class,
    ];
}
