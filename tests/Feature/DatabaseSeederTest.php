<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\User;
use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The teacher runs `php artisan migrate:fresh --seed`: make sure that always works.
 */
class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeding_creates_the_admin_and_demo_data(): void
    {
        $this->seed();

        $admin = User::firstWhere('email', 'admin@example.com');
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(password_verify('password', $admin->password));
        $this->assertSame(11, User::count());
        $this->assertSame(20, Exercise::count());
        $this->assertGreaterThan(50, WorkoutSet::count());
    }
}
