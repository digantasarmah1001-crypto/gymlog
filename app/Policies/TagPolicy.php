<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

/**
 * Tags are a shared list (like exercises): everyone can use them on their workouts,
 * only admins create, rename or delete them.
 */
class TagPolicy
{
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Tag $tag): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $user->is_admin;
    }
}
