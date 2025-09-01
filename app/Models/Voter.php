<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voter extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'full_name',
        'email',
        'phone',
        'external_id',
        'status',
        'verified_at',
        'voted_at',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'voted_at' => 'datetime',
        ];
    }

    // Status checking methods
    public function hasVoted(): bool
    {
        return $this->status === 'voted';
    }

    public function isVerified(): bool
    {
        return in_array($this->status, ['verified', 'voted']);
    }

    public function isRevoked(): bool
    {
        return $this->status === 'revoked';
    }

    // Relationships
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function voterTokens(): HasMany
    {
        return $this->hasMany(VoterToken::class);
    }

    public function ballots(): HasMany
    {
        return $this->hasMany(Ballot::class);
    }
}
