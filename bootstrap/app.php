<?php

use App\Http\Middleware\AuditRequest;
use App\Http\Middleware\EnsureElectionWindow;
use App\Http\Middleware\RateLimitVoting;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\ValidateVotingToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->append(SecurityHeaders::class);
        
        // Middleware aliases
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'election.window' => EnsureElectionWindow::class,
            'voting.token' => ValidateVotingToken::class,
            'audit' => AuditRequest::class,
            'security.headers' => SecurityHeaders::class,
            'throttle.voting' => RateLimitVoting::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
