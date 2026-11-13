<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Log in as a normal athlete (a new one, or the one you pass) and return them.
     */
    protected function signIn(?User $user = null): User
    {
        $user ??= User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    /**
     * Log in as an admin and return them.
     */
    protected function signInAsAdmin(): User
    {
        return $this->signIn(User::factory()->admin()->create());
    }
}
