<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'auth:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'errors' => ['email' => ['Too many login attempts. Try again in ' . $seconds . ' seconds.']]
            ], 429);
        }

        $response = $next($request);

        if ($response->getStatusCode() >= 400) {
            RateLimiter::hit($key, 300); // 5 minutes
        }

        return $response;
    }
}