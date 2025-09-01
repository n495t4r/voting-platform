<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BallotSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'ballot_id',
        'position_id',
        'candidate_id',
        'rank',
    ];

    public function ballot(): BelongsTo
    {
        return $this->belongsTo(Ballot::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
