<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Voter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class VoterOnboardingService
{
    public function __construct(
        private TokenService $tokenService,
        private NotificationService $notificationService,
        private AuditService $auditService
    ) {}

    /**
     * Import voters from CSV data.
     */
    public function importVoters(Election $election, array $votersData): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = [];
        // dd($votersData);
        foreach ($votersData as $index => $voterData) {
            try {
                $this->validateVoterData($voterData);

                // Check for duplicates
                if ($this->isDuplicate($election, $voterData)) {
                    $skipped++;
                    continue;
                }

                Voter::create([
                    'election_id' => $election->id,
                    'full_name' => $voterData['full_name'],
                    'email' => $voterData['email'] ?? null,
                    'phone' => $voterData['phone'] ?? null,
                    'external_id' => $voterData['external_id'] ?? null,
                    'status' => 'registered',
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $this->auditService->log('voters_imported', [
            'election_id' => $election->id,
            'imported' => $imported,
            'skipped' => $skipped,
            'errors_count' => count($errors),
        ]);

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * Send voting invitations to voters.
     */
    public function sendInvitations(Election $election, ?Collection $voters = null, $regenerate=false): int
    {

        if(!$regenerate){
            $voters = $voters ?? $election->voters()->whereIn('status', ['registered'])->get();
        }else{
            $voters = $voters ?? $election->voters()->get();
        }

        $sent = 0;

        foreach ($voters as $voter) {
            try {
                // Generate voting token
                $token = $this->tokenService->mint($voter, $election);

                // Send invitation
                $this->notificationService->sendVotingInvitation($voter, $election, $token);

                $sent++;

                $voter->update([
                    'status' => 'invited'
                ]);
            } catch (\Exception $e) {
                // Log error but continue with other voters
                $this->auditService->log('invitation_failed', [
                    'election_id' => $election->id,
                    'voter_id' => $voter->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }



        $this->auditService->log('invitations_sent', [
            'election_id' => $election->id,
            'sent_count' => $sent,
        ]);

        return $sent;
    }

    /**
     * Validate voter data structure.
     */
    private function validateVoterData(array $data): void
    {
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'external_id' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        // At least email or phone must be provided
        if (empty($data['email']) && empty($data['phone'])) {
            throw new \InvalidArgumentException('Either email or phone must be provided.');
        }
    }

    /**
     * Check if voter already exists in election.
     */
    private function isDuplicate(Election $election, array $voterData): bool
    {
        $query = $election->voters();

        if (!empty($voterData['email'])) {
            $query->where('email', $voterData['email']);
        } elseif (!empty($voterData['phone'])) {
            $query->where('phone', $voterData['phone']);
        }

        return $query->exists();
    }

    /**
     * Mark voter as verified.
     */
    public function markVerified(Voter $voter): void
    {
        $voter->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        $this->auditService->log('voter_verified', [
            'election_id' => $voter->election_id,
            'voter_id' => $voter->id,
        ]);
    }
}
