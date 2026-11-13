<?php

namespace App\Policies;

use App\Models\Exercise;
use App\Models\User;

/**
 * The exercise library is shared: everyone (even guests) can read it, only admins change it.
 */
class ExercisePolicy
{
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Exercise $exercise): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Exercise $exercise): bool
    {
        return $user->is_admin;
    }
}
