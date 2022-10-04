<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

final class LogActivity
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        $response = $next($request);
        $log = [
            'URI' => $request->getUri(),
            'METHOD' => $request->getMethod(),
            'HEADERS' => $request->header(),
            'REQUEST_BODY' => $request->all(),
        ];
        Log::info('{ REQUEST :'.json_encode($log).'}');
        $log = [
            'HTTP_STATUS' => $response->status(),
            'HEADERS' => $response->headers->all(),
            'RESPONSE_BODY' => $response->getContent(),
        ];
        Log::info('{ RESPONSE :'.json_encode($log).'}');

        return $response;
    }
}
