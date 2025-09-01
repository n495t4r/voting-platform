<?php

namespace App\Http\Middleware;

use App\Services\TokenService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateVotingToken
{
    public function __construct(
        private TokenService $tokenService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->route('token');
        
        if (!$token) {
            return response()->view('vote.error', [
                'message' => 'No voting token provided.',
                'canRetry' => false,
            ], 400);
        }

        try {
            $dto = $this->tokenService->validate($token);
            
            // Add validated data to request
            $request->merge([
                'validated_token' => $dto->token,
                'validated_voter' => $dto->voter,
                'validated_election' => $dto->election,
            ]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->view('vote.error', [
                'message' => $e->getMessage(),
                'canRetry' => str_contains($e->getMessage(), 'expired'),
            ], 403);
        }
    }
}
