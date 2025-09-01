<?php

namespace App\Policies;

use App\Models\Election;
use App\Models\User;

class ElectionPolicy
{
    /**
     * Determine whether the user can view any elections.
     */
    public function viewAny(User $user): bool
    {
        return $user->isCommittee();
    }

    /**
     * Determine whether the user can view the election.
     */
    public function view(User $user, Election $election): bool
    {
        return $user->isCommittee();
    }

    /**
     * Determine whether the user can create elections.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the election.
     */
    public function update(User $user, Election $election): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the election.
     */
    public function delete(User $user, Election $election): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can manage voters for the election.
     */
    public function manageVoters(User $user, Election $election): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view results for the election.
     */
    public function viewResults(User $user, Election $election): bool
    {
        return $user->isCommittee() && $election->isClosed();
    }

    /**
     * Determine whether the user can export audit data for the election.
     */
    public function exportAudit(User $user, Election $election): bool
    {
        return $user->isAdmin();
    }
}
