<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkoutSet;

/**
 * A set follows the rules of its workout: if you may change the workout, you may change its sets.
 */
class WorkoutSetPolicy
{
    public function view(User $user, WorkoutSet $set): bool
    {
        return $user->can('view', $set->workout);
    }

    public function update(User $user, WorkoutSet $set): bool
    {
        return $user->can('update', $set->workout);
    }

    public function delete(User $user, WorkoutSet $set): bool
    {
        return $user->can('update', $set->workout);
    }
}
