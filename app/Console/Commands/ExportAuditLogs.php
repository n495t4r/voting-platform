<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Services\AuditService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'voting:export-audit 
                            {election? : The election ID to export audit logs for}
                            {--format=json : Export format (json|csv)}
                            {--output= : Output file path}';

    /**
     * The console command description.
     */
    protected $description = 'Export audit logs for an election or the entire system';

    public function __construct(
        private AuditService $auditService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $electionId = $this->argument('election');
        $format = $this->option('format');
        $output = $this->option('output');

        if ($electionId) {
            $election = Election::find($electionId);
            if (!$election) {
                $this->error("Election with ID {$electionId} not found.");
                return 1;
            }

            $this->info("Exporting audit logs for election: {$election->title}");
            $data = $this->auditService->generateAuditBundle($election);
            $filename = $output ?: "audit-election-{$election->id}-" . now()->format('Y-m-d-H-i-s') . ".{$format}";
        } else {
            $this->info("Exporting system-wide audit logs");
            // Export all audit logs
            $data = [
                'system_audit' => true,
                'exported_at' => now()->toISOString(),
                'audit_logs' => \App\Models\AuditLog::orderBy('created_at')->get()->toArray(),
            ];
            $filename = $output ?: "audit-system-" . now()->format('Y-m-d-H-i-s') . ".{$format}";
        }

        // Export data
        if ($format === 'csv') {
            $content = $this->convertToCsv($data['audit_logs'] ?? $data);
        } else {
            $content = json_encode($data, JSON_PRETTY_PRINT);
        }

        Storage::disk('local')->put("exports/{$filename}", $content);
        
        $this->info("Audit logs exported to: storage/app/exports/{$filename}");
        
        if (isset($data['integrity_verified'])) {
            if ($data['integrity_verified']) {
                $this->info("✓ Hash chain integrity verified");
            } else {
                $this->warn("⚠ Hash chain integrity check failed");
            }
        }

        return 0;
    }

    /**
     * Convert audit logs to CSV format.
     */
    private function convertToCsv(array $logs): string
    {
        if (empty($logs)) {
            return "No audit logs found\n";
        }

        $csv = "ID,Election ID,Actor ID,Actor Type,Event,IP Address,User Agent,Created At\n";
        
        foreach ($logs as $log) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                $log['id'] ?? '',
                $log['election_id'] ?? '',
                $log['actor_id'] ?? '',
                $log['actor_type'] ?? '',
                $log['event'] ?? '',
                $log['ip_address'] ?? '',
                '"' . str_replace('"', '""', $log['user_agent'] ?? '') . '"',
                $log['created_at'] ?? ''
            );
        }

        return $csv;
    }
}
