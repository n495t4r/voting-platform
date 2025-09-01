<?php

namespace App\Http\Middleware;

use App\Models\Election;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureElectionWindow
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $election = $request->route('election');
        
        if ($election instanceof Election) {
            // Check if election is in valid voting window
            if (!$election->isOpen()) {
                abort(403, 'Voting is not currently open for this election.');
            }
        }

        return $next($request);
    }
}
