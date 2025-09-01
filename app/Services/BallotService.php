<?php

namespace App\Services;

use App\Models\Ballot;
use App\Models\BallotSelection;
use App\Models\Election;
use App\Models\Voter;
use Illuminate\Support\Str;

class BallotService
{
    public function __construct(
        private AuditService $auditService,
        private FeatureFlagService $featureFlagService
    ) {}

    /**
     * Build ballot structure for a voter.
     */
    public function buildForVoter(Voter $voter, Election $election): array
    {
        $positions = $election->positions()->with('candidates')->get();
        
        return [
            'election' => $election,
            'voter' => $voter,
            'positions' => $positions,
            'can_revote' => $this->featureFlagService->isEnabled('allow_revote_until_close', $election),
            'existing_ballot' => $this->getExistingBallot($voter, $election),
        ];
    }

    /**
     * Submit a ballot with selections.
     */
    public function submit(Voter $voter, Election $election, array $selections): Ballot
    {
        // Validate selections
        $this->validateSelections($election, $selections);

        // Check if revoting is allowed
        $existingBallot = $this->getExistingBallot($voter, $election);
        $canRevote = $this->featureFlagService->isEnabled('allow_revote_until_close', $election);

        if ($existingBallot && !$canRevote) {
            throw new \InvalidArgumentException('Revoting is not allowed for this election.');
        }

        // Create new ballot
        $revision = $existingBallot ? $existingBallot->revision + 1 : 1;
        
        $ballot = Ballot::create([
            'election_id' => $election->id,
            'ballot_uid' => Str::uuid(),
            'voter_id' => $voter->id,
            'submitted_at' => now(),
            'revision' => $revision,
            'hash_chain' => $this->generateHashChain($voter, $election, $selections),
        ]);

        // Store selections
        foreach ($selections as $positionId => $candidateIds) {
            foreach ((array) $candidateIds as $rank => $candidateId) {
                BallotSelection::create([
                    'ballot_id' => $ballot->id,
                    'position_id' => $positionId,
                    'candidate_id' => $candidateId,
                    'rank' => is_numeric($rank) ? $rank + 1 : null,
                ]);
            }
        }

        // Update voter status
        $voter->update([
            'status' => 'voted',
            'voted_at' => now(),
        ]);

        // Log the vote submission
        $this->auditService->log('ballot_submitted', [
            'election_id' => $election->id,
            'voter_id' => $voter->id,
            'ballot_uid' => $ballot->ballot_uid,
            'revision' => $revision,
        ]);

        return $ballot;
    }

    /**
     * Validate ballot selections against position rules.
     */
    private function validateSelections(Election $election, array $selections): void
    {
        $positions = $election->positions()->with('candidates')->get()->keyBy('id');

        foreach ($selections as $positionId => $candidateIds) {
            $position = $positions->get($positionId);
            
            if (!$position) {
                throw new \InvalidArgumentException("Invalid position ID: {$positionId}");
            }

            $candidateIds = (array) $candidateIds;
            $selectionCount = count($candidateIds);

            // Check min/max selection rules
            if ($selectionCount < $position->min_select) {
                throw new \InvalidArgumentException(
                    "Position '{$position->title}' requires at least {$position->min_select} selections."
                );
            }

            if ($selectionCount > $position->max_select) {
                throw new \InvalidArgumentException(
                    "Position '{$position->title}' allows at most {$position->max_select} selections."
                );
            }

            // Validate candidate IDs belong to this position
            $validCandidateIds = $position->candidates->pluck('id')->toArray();
            foreach ($candidateIds as $candidateId) {
                if (!in_array($candidateId, $validCandidateIds)) {
                    throw new \InvalidArgumentException("Invalid candidate ID: {$candidateId}");
                }
            }
        }
    }

    /**
     * Get existing ballot for voter (latest revision).
     */
    private function getExistingBallot(Voter $voter, Election $election): ?Ballot
    {
        return Ballot::where('voter_id', $voter->id)
            ->where('election_id', $election->id)
            ->orderBy('revision', 'desc')
            ->first();
    }

    /**
     * Generate hash chain for ballot integrity.
     */
    private function generateHashChain(Voter $voter, Election $election, array $selections): string
    {
        $data = [
            'voter_id' => $voter->id,
            'election_id' => $election->id,
            'selections' => $selections,
            'timestamp' => now()->toISOString(),
        ];

        return hash('sha256', json_encode($data));
    }

    /**
     * Get the last submitted ballot UID for receipt.
     */
    public function getLastBallotUid(Voter $voter, Election $election): ?string
    {
        $ballot = $this->getExistingBallot($voter, $election);
        return $ballot?->ballot_uid;
    }
}
