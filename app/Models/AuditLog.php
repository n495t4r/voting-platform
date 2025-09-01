<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'actor_id',
        'actor_type',
        'event',
        'payload',
        'ip_address',
        'user_agent',
        'previous_hash',
        'current_hash',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
