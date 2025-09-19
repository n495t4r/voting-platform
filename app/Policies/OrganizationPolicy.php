<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    /**
     * Determine whether the user can view any elections.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the election.
     */
    public function view(User $user, Organization $organization): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create Organization.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the Organization.
     */
    public function update(User $user, Organization $organization): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the organisation.
     */
    public function delete(User $user, Organization $organization): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can manage voters for the election.
     */
    public function manageElections(User $user, Organization $organization): bool
    {
        return $user->isSuperAdmin();
    }
}
