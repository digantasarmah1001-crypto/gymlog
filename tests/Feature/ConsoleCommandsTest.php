<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsoleCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_weekly_summary_lists_athletes_who_trained(): void
    {
        $ana = User::factory()->create(['name' => 'Ana']);
        WorkoutSet::factory()->for(Workout::factory()->for($ana)->create(['date' => today()]))->create(['reps' => 10, 'weight_kg' => 50]);
        User::factory()->create(['name' => 'Lazy Larry']);

        $this->artisan('gymlog:weekly-summary')
            ->expectsOutputToContain('Ana')
            ->doesntExpectOutputToContain('Lazy Larry')
            ->assertSuccessful();
    }

    public function test_weekly_summary_when_nobody_trained(): void
    {
        $this->artisan('gymlog:weekly-summary')->expectsOutputToContain('Nobody trained')->assertSuccessful();
    }

    public function test_records_can_be_recalculated(): void
    {
        $user = User::factory()->create();
        $squat = Exercise::factory()->create();
        WorkoutSet::factory()->for($squat)->for(Workout::factory()->for($user)->create(['date' => today()->subWeek()]))->create(['weight_kg' => 100]);
        $heavier = WorkoutSet::factory()->for($squat)->for(Workout::factory()->for($user)->create(['date' => today()]))->create(['weight_kg' => 110]);
        $heavier->updateQuietly(['is_pr' => false]); // pretend the flag got lost

        $this->artisan('gymlog:recalculate-records')->expectsOutputToContain('1 sets')->assertSuccessful();

        $this->assertTrue($heavier->fresh()->is_pr);
    }

    public function test_visible_to_scope(): void
    {
        $me = User::factory()->create();
        Workout::factory()->for($me)->create();
        Workout::factory()->create();

        $this->assertCount(1, Workout::visibleTo($me)->get());
        $this->assertCount(2, Workout::visibleTo(User::factory()->admin()->create())->get());
    }
}
