<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Workout;
use App\Policies\WorkoutPolicy;
use PHPUnit\Framework\TestCase;

/**
 * The policy is plain PHP logic, so we can test it with models that are never saved (no database).
 */
class WorkoutPolicyTest extends TestCase
{
    private function user(int $id, bool $admin = false): User
    {
        return (new User)->forceFill(['id' => $id, 'is_admin' => $admin]);
    }

    private function workoutOf(User $owner): Workout
    {
        return (new Workout)->forceFill(['user_id' => $owner->id]);
    }

    public function test_owner_can_view_update_and_delete(): void
    {
        $owner = $this->user(1);
        $workout = $this->workoutOf($owner);
        $policy = new WorkoutPolicy;

        $this->assertTrue($policy->view($owner, $workout));
        $this->assertTrue($policy->update($owner, $workout));
        $this->assertTrue($policy->delete($owner, $workout));
    }

    public function test_other_athletes_cannot(): void
    {
        $workout = $this->workoutOf($this->user(1));
        $stranger = $this->user(2);
        $policy = new WorkoutPolicy;

        $this->assertFalse($policy->view($stranger, $workout));
        $this->assertFalse($policy->update($stranger, $workout));
        $this->assertFalse($policy->delete($stranger, $workout));
    }

    public function test_admins_can(): void
    {
        $workout = $this->workoutOf($this->user(1));
        $admin = $this->user(99, admin: true);

        $this->assertTrue((new WorkoutPolicy)->update($admin, $workout));
    }
}
