<?php

namespace App\Http\Middleware;

use App\Builders\OperatorBuilder;
use App\Models\Operator;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class EnsureTokenExists
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse
    {
        if (!$request->bearerToken()) {
            return new Response(null, 401);
        }
        $operator = Operator::query()
            ->tap(fn (OperatorBuilder $b) => $b->whereAccessTokenIs($request->bearerToken()))
            ->first();
        $request->setUserResolver(function () use ($operator) { return $operator; });
        if (!$request->user()) {
            return new Response(null, 401);
        }

        return $next($request);
    }
}
