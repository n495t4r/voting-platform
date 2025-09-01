<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Election;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an audit event.
     */
    public function log(
        string $event, 
        array $payload = [], 
        ?Election $election = null,
        ?int $actorId = null
    ): AuditLog {
        $actor = Auth::user();
        $actorId = $actorId ?? $actor?->id;

        // Get previous hash for chain integrity
        $previousHash = $this->getLastHash($election);
        
        // Generate current hash
        $currentHash = $this->generateHash($event, $payload, $actorId, $previousHash);

        return AuditLog::create([
            'election_id' => $election?->id,
            'actor_id' => $actorId,
            'actor_type' => $actor ? get_class($actor) : 'System',
            'event' => $event,
            'payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'previous_hash' => $previousHash,
            'current_hash' => $currentHash,
        ]);
    }

    /**
     * Generate audit bundle for export.
     */
    public function generateAuditBundle(Election $election): array
    {
        $logs = $election->auditLogs()
            ->orderBy('created_at')
            ->get();

        // Verify hash chain integrity
        $integrityCheck = $this->verifyHashChain($logs);

        return [
            'election' => [
                'id' => $election->id,
                'title' => $election->title,
                'slug' => $election->slug,
                'starts_at' => $election->starts_at,
                'ends_at' => $election->ends_at,
                'status' => $election->status,
            ],
            'audit_logs' => $logs->toArray(),
            'integrity_verified' => $integrityCheck,
            'generated_at' => now()->toISOString(),
            'bundle_hash' => hash('sha256', json_encode($logs->toArray())),
        ];
    }

    /**
     * Get the last hash in the chain.
     */
    private function getLastHash(?Election $election): ?string
    {
        $query = AuditLog::orderBy('created_at', 'desc');
        
        if ($election) {
            $query->where('election_id', $election->id);
        }

        return $query->first()?->current_hash;
    }

    /**
     * Generate hash for audit log entry.
     */
    private function generateHash(string $event, array $payload, ?int $actorId, ?string $previousHash): string
    {
        $data = [
            'event' => $event,
            'payload' => $payload,
            'actor_id' => $actorId,
            'previous_hash' => $previousHash,
            'timestamp' => now()->toISOString(),
        ];

        return hash('sha256', json_encode($data));
    }

    /**
     * Verify hash chain integrity.
     */
    private function verifyHashChain($logs): bool
    {
        $previousHash = null;

        foreach ($logs as $log) {
            $expectedHash = $this->generateHash(
                $log->event,
                $log->payload ?? [],
                $log->actor_id,
                $previousHash
            );

            if ($log->current_hash !== $expectedHash) {
                return false;
            }

            $previousHash = $log->current_hash;
        }

        return true;
    }
}
