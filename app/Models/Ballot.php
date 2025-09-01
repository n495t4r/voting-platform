<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ballot extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'ballot_uid',
        'voter_id',
        'submitted_at',
        'revision',
        'hash_chain',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
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

    public function selections(): HasMany
    {
        return $this->hasMany(BallotSelection::class);
    }
}
