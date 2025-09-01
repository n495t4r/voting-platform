<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Clean up expired tokens daily
        $schedule->command('model:prune', ['--model' => 'App\\Models\\VoterToken'])
            ->daily()
            ->description('Clean up expired voter tokens');

        // Archive old audit logs (keep for 7 years by default)
        $schedule->command('model:prune', ['--model' => 'App\\Models\\AuditLog'])
            ->monthly()
            ->description('Archive old audit logs');

        // Generate daily system health reports
        $schedule->command('voting:export-audit', ['--format' => 'json'])
            ->dailyAt('02:00')
            ->description('Generate daily audit export');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
