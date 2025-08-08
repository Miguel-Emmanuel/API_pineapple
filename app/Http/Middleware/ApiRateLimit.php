<?php

namespace App\Http\Middleware;

use Illuminate\Cache\RateLimiter;
use Closure;

class ApiRateLimit
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next)
    {
        $key = $request->ip();

        if ($this->limiter->tooManyAttempts($key, 60)) { // 60 intentos por minuto
            return response()->json([
                'error' => 'Too many requests',
                'retry_after' => $this->limiter->availableIn($key)
            ], 429);
        }

        $this->limiter->hit($key);

        return $next($request);
    }
}
