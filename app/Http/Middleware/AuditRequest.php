<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuditRequest
{
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only audit specific actions
        if ($this->shouldAudit($request, $response)) {
            $this->auditService->log(
                $this->getEventName($request),
                [
                    'method' => $request->method(),
                    'url' => $request->url(),
                    'status_code' => $response->getStatusCode(),
                    'user_agent' => $request->userAgent(),
                ],
                $this->getElectionFromRequest($request)
            );
        }

        return $response;
    }

    /**
     * Determine if request should be audited.
     */
    private function shouldAudit(Request $request, Response $response): bool
    {
        // Audit admin actions
        if ($request->is('admin/*') && Auth::check()) {
            return true;
        }

        // Audit voting actions
        if ($request->is('vote/*') && $request->isMethod('POST')) {
            return true;
        }

        // Audit authentication
        if ($request->is('login') && $request->isMethod('POST')) {
            return true;
        }

        return false;
    }

    /**
     * Get event name for audit log.
     */
    private function getEventName(Request $request): string
    {
        if ($request->is('admin/elections/*/open')) {
            return 'election_opened';
        }

        if ($request->is('admin/elections/*/close')) {
            return 'election_closed';
        }

        if ($request->is('vote/*') && $request->isMethod('POST')) {
            return 'vote_submitted';
        }

        if ($request->is('login')) {
            return 'user_login_attempt';
        }

        return 'admin_action';
    }

    /**
     * Extract election from request if available.
     */
    private function getElectionFromRequest(Request $request): ?\App\Models\Election
    {
        $election = $request->route('election');
        return $election instanceof \App\Models\Election ? $election : null;
    }
}
