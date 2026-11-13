<?php

namespace Tests\Feature;

use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitPreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_kg_or_lb_choice_is_kept_in_the_session(): void
    {
        $set = WorkoutSet::factory()->create(['weight_kg' => 100]);
        $this->signIn($set->workout->user);

        $this->get(route('workouts.show', $set->workout))->assertSee('100 kg');

        $this->from('/')->post(route('unit.update'), ['unit' => 'lb'])->assertRedirect('/')->assertSessionHas('unit', 'lb');

        $this->get(route('workouts.show', $set->workout))->assertSee('220.5 lb')->assertDontSee('100 kg');
        $this->assertDatabaseHas('workout_sets', ['id' => $set->id, 'weight_kg' => 100]); // stored value unchanged
    }

    public function test_only_kg_and_lb_are_allowed(): void
    {
        $this->post(route('unit.update'), ['unit' => 'stone'])->assertSessionHasErrors('unit');
    }
}
