<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Voter;
use App\Models\VoterToken;
use App\DTOs\ValidatedTokenDTO;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TokenService
{
    /**
     * Generate a secure voting token for a voter.
     */
    public function mint(
        Voter $voter, 
        Election $election, 
        int $maxUsage = 1, 
        ?Carbon $expiresAt = null
    ): string {
        // Generate a cryptographically secure random token
        $rawToken = Str::random(64);
        $tokenHash = hash('sha256', $rawToken);

        // Default expiry from config
        $expiresAt = $expiresAt ?? now()->addMinutes(config('voting.token_ttl_minutes', 1440));

        // Store the hashed token
        VoterToken::create([
            'election_id' => $election->id,
            'voter_id' => $voter->id,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
            'max_usage' => $maxUsage,
            'usage_count' => 0,
        ]);

        return $rawToken;
    }

    /**
     * Validate a raw token and return voter/election data.
     */
    public function validate(string $rawToken): ValidatedTokenDTO
    {
        $tokenHash = hash('sha256', $rawToken);
        
        $voterToken = VoterToken::with(['voter', 'election'])
            ->where('token_hash', $tokenHash)
            ->first();

        if (!$voterToken) {
            throw new \InvalidArgumentException('Invalid token.');
        }

        if ($voterToken->isExpired()) {
            throw new \InvalidArgumentException('Token has expired.');
        }

        if ($voterToken->isExhausted()) {
            throw new \InvalidArgumentException('Token usage limit exceeded.');
        }

        return new ValidatedTokenDTO(
            token: $voterToken,
            voter: $voterToken->voter,
            election: $voterToken->election
        );
    }

    /**
     * Consume a token (increment usage count).
     */
    public function consume(VoterToken $token): void
    {
        $token->increment('usage_count');
        
        // Mark as used if this was the final usage
        if ($token->usage_count >= $token->max_usage) {
            $token->update(['used_at' => now()]);
        }
    }

    /**
     * Revoke all tokens for a voter in an election.
     */
    public function revokeVoterTokens(Voter $voter, Election $election): void
    {
        VoterToken::where('voter_id', $voter->id)
            ->where('election_id', $election->id)
            ->update(['used_at' => now(), 'usage_count' => 999]);
    }
}
