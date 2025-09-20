<?php

namespace App\Policies;

use App\Models\AiPersona;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AiPersonaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AiPersona $aiPersona): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * Only admins can create AI personas.
     */
    public function create(User $user): bool
    {
        return $user->is_admin ?? false;
    }

    /**
     * Determine whether the user can update the model.
     * Only admins can update AI personas.
     */
    public function update(User $user, AiPersona $aiPersona): bool
    {
        return $user->is_admin ?? false;
    }

    /**
     * Determine whether the user can delete the model.
     * Only admins can delete AI personas.
     */
    public function delete(User $user, AiPersona $aiPersona): bool
    {
        return $user->is_admin ?? false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AiPersona $aiPersona): bool
    {
        return $user->is_admin ?? false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AiPersona $aiPersona): bool
    {
        return $user->is_admin ?? false;
    }
}