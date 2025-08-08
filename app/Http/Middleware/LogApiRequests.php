<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogApiRequests
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        Log::channel('api')->info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user' => $request->user()?->id,
            'status' => $response->status(),
            'duration' => defined('LARAVEL_START') ? round((microtime(true) - LARAVEL_START) * 1000) : 0
        ]);

        return $response;
    }
}
