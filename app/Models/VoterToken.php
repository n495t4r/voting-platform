<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoterToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'voter_id',
        'token_hash',
        'expires_at',
        'used_at',
        'usage_count',
        'max_usage',
        'channel',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    // Token validation methods
    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function isExhausted(): bool
    {
        return $this->usage_count >= $this->max_usage;
    }

    public function canBeUsed(): bool
    {
        return !$this->isExpired() && !$this->isExhausted();
    }

    // Relationships
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function voter(): BelongsTo
    {
        return $this->belongsTo(Voter::class);
    }
}
