<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitVoting
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->resolveRequestSignature($request);

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts())) {
            return response()->view('vote.error', [
                'message' => 'Too many voting attempts. Please try again later.',
                'canRetry' => true,
            ], 429);
        }

        RateLimiter::hit($key, $this->decayMinutes() * 60);

        return $next($request);
    }

    /**
     * Resolve request signature for rate limiting.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1($request->ip() . '|' . $request->route('token'));
    }

    /**
     * Get the maximum number of attempts allowed.
     */
    protected function maxAttempts(): int
    {
        return config('voting.max_voting_attempts', 5);
    }

    /**
     * Get the number of minutes until attempts are reset.
     */
    protected function decayMinutes(): int
    {
        return config('voting.voting_rate_limit_minutes', 15);
    }
}
