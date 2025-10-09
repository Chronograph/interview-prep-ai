<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    /**
     * Determine whether the user can view any teams.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the team.
     */
    public function view(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id || $team->hasMember($user);
    }

    /**
     * Determine whether the user can create teams.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the team.
     */
    public function update(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id || $team->getMemberRole($user) === 'admin';
    }

    /**
     * Determine whether the user can delete the team.
     */
    public function delete(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id;
    }

    /**
     * Determine whether the user can restore the team.
     */
    public function restore(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the team.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id;
    }

    /**
     * Determine whether the user can invite members to the team.
     */
    public function inviteMembers(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id || $team->getMemberRole($user) === 'admin';
    }

    /**
     * Determine whether the user can remove members from the team.
     */
    public function removeMembers(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id || $team->getMemberRole($user) === 'admin';
    }

    /**
     * Determine whether the user can update member roles in the team.
     */
    public function updateMemberRoles(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id || $team->getMemberRole($user) === 'admin';
    }
}
