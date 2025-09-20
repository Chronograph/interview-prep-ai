<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InterviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own interviews
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Interview $interview): bool
    {
        return $user->id === $interview->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Authenticated users can create interviews
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Interview $interview): bool
    {
        // Users can only update their own interviews
        // and only if the interview is not completed
        return $user->id === $interview->user_id && 
               in_array($interview->status, ['pending', 'in_progress']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Interview $interview): bool
    {
        return $user->id === $interview->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Interview $interview): bool
    {
        return $user->id === $interview->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Interview $interview): bool
    {
        return $user->id === $interview->user_id;
    }

    /**
     * Determine whether the user can start an interview.
     */
    public function start(User $user, Interview $interview): bool
    {
        return $user->id === $interview->user_id && 
               $interview->status === 'pending';
    }

    /**
     * Determine whether the user can complete an interview.
     */
    public function complete(User $user, Interview $interview): bool
    {
        return $user->id === $interview->user_id && 
               $interview->status === 'in_progress';
    }

    /**
     * Determine whether the user can generate feedback for an interview.
     */
    public function generateFeedback(User $user, Interview $interview): bool
    {
        return $user->id === $interview->user_id && 
               $interview->status === 'completed';
    }
}
