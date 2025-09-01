<?php

namespace App\DTOs;

use App\Models\Election;
use App\Models\Voter;
use App\Models\VoterToken;

class ValidatedTokenDTO
{
    public function __construct(
        public VoterToken $token,
        public Voter $voter,
        public Election $election
    ) {}
}
