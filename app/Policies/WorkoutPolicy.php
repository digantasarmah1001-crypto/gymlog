<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workout;

/**
 * Workouts are private: only the athlete who logged them (or an admin) may see or change them.
 * Laravel finds this policy automatically because it's called {Model}Policy.
 */
class WorkoutPolicy
{
    public function view(User $user, Workout $workout): bool
    {
        return $user->id === $workout->user_id || $user->is_admin;
    }

    public function create(User $user): bool
    {
        return true; // every logged-in athlete can log workouts
    }

    public function update(User $user, Workout $workout): bool
    {
        return $user->id === $workout->user_id || $user->is_admin;
    }

    public function delete(User $user, Workout $workout): bool
    {
        return $user->id === $workout->user_id || $user->is_admin;
    }
}
