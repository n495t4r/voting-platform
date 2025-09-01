<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'slug',
        'description',
        'starts_at',
        'ends_at',
        'status',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'settings' => 'array',
        ];
    }

    // Status checking methods
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' && 
               now()->between($this->starts_at, $this->ends_at);
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed' || now()->isAfter($this->ends_at);
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class)->orderBy('order');
    }

    public function voters(): HasMany
    {
        return $this->hasMany(Voter::class);
    }

    public function ballots(): HasMany
    {
        return $this->hasMany(Ballot::class);
    }

    public function voterTokens(): HasMany
    {
        return $this->hasMany(VoterToken::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function featureFlags(): HasMany
    {
        return $this->hasMany(FeatureFlag::class);
    }

    public function candidates(): HasManyThrough
    {
        return $this->hasManyThrough(Candidate::class, Position::class);
    }
}
