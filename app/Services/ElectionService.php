<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ElectionService
{
    public function __construct(
        private AuditService $auditService,
        private FeatureFlagService $featureFlagService
    ) {}

    /**
     * Create a new election.
     */
    public function create(array $data): Election
    {
        $election = Election::create([
            'organization_id' => $data['organization_id'],
            'title' => $data['title'],
            'slug' => $data['slug'] ?? Str::slug($data['title']),
            'description' => $data['description'] ?? null,
            'starts_at' => Carbon::parse($data['starts_at']),
            'ends_at' => Carbon::parse($data['ends_at']),
            'status' => 'draft',
            'settings' => $data['settings'] ?? [],
        ]);

        $this->auditService->log('election_created', [
            'election_id' => $election->id,
            'title' => $election->title,
        ]);

        return $election;
    }

    /**
     * Schedule an election to open.
     */
    public function schedule(Election $election): bool
    {
        if (!$election->isDraft()) {
            throw new \InvalidArgumentException('Only draft elections can be scheduled.');
        }

        $election->update(['status' => 'scheduled']);

        $this->auditService->log('election_scheduled', [
            'election_id' => $election->id,
            'starts_at' => $election->starts_at,
            'ends_at' => $election->ends_at,
        ]);

        return true;
    }

    /**
     * Open an election for voting.
     */
    public function open(Election $election): bool
    {
        if (!in_array($election->status, ['scheduled', 'draft'])) {
            throw new \InvalidArgumentException('Election cannot be opened in current status.');
        }

        if (now()->isBefore($election->starts_at)) {
            throw new \InvalidArgumentException('Election cannot be opened before start time.');
        }

        $election->update(['status' => 'open']);

        $this->auditService->log('election_opened', [
            'election_id' => $election->id,
            'opened_at' => now(),
        ]);

        return true;
    }

    /**
     * Close an election.
     */
    public function close(Election $election): bool
    {
        if ($election->status !== 'open') {
            throw new \InvalidArgumentException('Only open elections can be closed.');
        }

        $election->update(['status' => 'closed']);

        $this->auditService->log('election_closed', [
            'election_id' => $election->id,
            'closed_at' => now(),
            'total_ballots' => $election->ballots()->count(),
        ]);

        return true;
    }

    /**
     * Get election turnout statistics.
     */
    public function getTurnoutStats(Election $election): array
    {
        $totalVoters = $election->voters()->count();
        $verifiedVoters = $election->voters()->where('status', 'verified')->count();
        $votedCount = $election->voters()->where('status', 'voted')->count();

        return [
            'total_voters' => $totalVoters,
            'verified_voters' => $verifiedVoters,
            'voted_count' => $votedCount,
            'turnout_percentage' => $totalVoters > 0 ? round(($votedCount / $totalVoters) * 100, 2) : 0,
        ];
    }

    /**
     * Check if election is in valid voting window.
     */
    public function isInVotingWindow(Election $election): bool
    {
        return $election->status === 'open' && 
               now()->between($election->starts_at, $election->ends_at);
    }
}
