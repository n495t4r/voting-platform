<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'title',
        'description',
        'min_select',
        'max_select',
        'order',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class)->orderBy('order');
    }

    public function ballotSelections(): HasMany
    {
        return $this->hasMany(BallotSelection::class);
    }
}
