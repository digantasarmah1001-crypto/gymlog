<?php

namespace Tests\Feature;

use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_pages_show_the_custom_404(): void
    {
        $this->get('/does-not-exist')->assertNotFound()->assertSee('Missed the rep');
        $this->get('/exercises/99999')->assertNotFound()->assertSee('Missed the rep'); // route model binding
    }

    public function test_forbidden_pages_show_the_custom_403(): void
    {
        $this->signIn();

        $this->get(route('workouts.show', Workout::factory()->create()))->assertForbidden()->assertSee('Not your workout');
    }

    public function test_user_input_is_escaped(): void
    {
        $me = $this->signIn();
        $workout = Workout::factory()->for($me)->create(['title' => '<script>alert(1)</script>']);

        $this->get(route('workouts.show', $workout))
            ->assertDontSee('<script>alert(1)</script>', false)  // false = check the raw HTML
            ->assertSee('&lt;script&gt;', false);               // shown as text, not run
    }
}
