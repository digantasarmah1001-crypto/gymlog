<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_see_the_app_and_a_call_to_register(): void
    {
        $exercise = Exercise::factory()->create();

        $this->get('/')->assertOk()
            ->assertSee('GymLog')
            ->assertSee($exercise->name)
            ->assertSee('Create account');
    }

    public function test_athletes_see_only_their_own_latest_workouts(): void
    {
        $me = $this->signIn();
        Workout::factory()->for($me)->create(['title' => 'Mine']);
        Workout::factory()->create(['title' => 'Not mine']);

        $this->get('/')->assertSee('Mine')->assertDontSee('Not mine');
    }

    public function test_the_page_still_works_when_served_from_a_real_cache(): void
    {
        // Tests normally use the in-memory "array" cache. A real store (file/database) serializes
        // the values, which is where caching Eloquent objects went wrong once: test that case.
        config(['cache.default' => 'file']);
        cache()->flush();
        $exercise = Exercise::factory()->create();

        $this->get('/')->assertOk()->assertSee($exercise->name); // fills the cache
        $this->get('/')->assertOk()->assertSee($exercise->name); // reads from the cache

        cache()->flush();
    }

    public function test_totals_are_cached_and_cleared_by_model_events(): void
    {
        $this->get('/');
        $this->assertTrue(cache()->has('home.stats'));

        WorkoutSet::factory()->create(); // WorkoutSetObserver clears the cache

        $this->assertFalse(cache()->has('home.stats'));
    }
}
