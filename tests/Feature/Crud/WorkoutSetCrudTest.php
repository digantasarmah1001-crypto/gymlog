<?php

namespace Tests\Feature\Crud;

use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkoutSetCrudTest extends TestCase
{
    use RefreshDatabase;

    private Workout $workout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workout = Workout::factory()->for($this->signIn())->create();
    }

    private function validSet(array $overrides = []): array
    {
        return array_merge([
            'exercise_id' => Exercise::factory()->create()->id,
            'set_number' => 1,
            'reps' => 8,
            'weight_kg' => 82.5,
        ], $overrides);
    }

    public function test_all_pages_render(): void
    {
        $set = WorkoutSet::factory()->for($this->workout)->create();

        $this->get(route('workouts.sets.index', $this->workout))->assertOk()->assertSee($set->exercise->name);
        $this->get(route('workouts.sets.create', $this->workout))->assertOk();
        $this->get(route('sets.show', $set))->assertOk()->assertSee($set->exercise->name);
        $this->get(route('sets.edit', $set))->assertOk();
    }

    public function test_a_set_can_be_added(): void
    {
        $this->post(route('workouts.sets.store', $this->workout), $this->validSet())
            ->assertRedirect(route('workouts.show', $this->workout))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('workout_sets', ['workout_id' => $this->workout->id, 'reps' => 8, 'weight_kg' => 82.5]);
    }

    public function test_invalid_input_is_rejected(): void
    {
        $this->post(route('workouts.sets.store', $this->workout), ['exercise_id' => 999, 'set_number' => 0, 'reps' => 0, 'weight_kg' => -5])
            ->assertSessionHasErrors(['exercise_id', 'set_number', 'reps', 'weight_kg']);
    }

    public function test_a_set_number_can_only_be_used_once_per_workout(): void
    {
        WorkoutSet::factory()->for($this->workout)->create(['set_number' => 1]);

        $this->post(route('workouts.sets.store', $this->workout), $this->validSet(['set_number' => 1]))
            ->assertSessionHasErrors('set_number');

        // ...but the same number in ANOTHER workout is fine.
        $otherWorkout = Workout::factory()->for(auth()->user())->create();
        $this->post(route('workouts.sets.store', $otherWorkout), $this->validSet(['set_number' => 1]))
            ->assertSessionHasNoErrors();
    }

    public function test_a_set_can_be_updated_keeping_its_number(): void
    {
        $set = WorkoutSet::factory()->for($this->workout)->create(['set_number' => 2]);

        $this->put(route('sets.update', $set), $this->validSet(['set_number' => 2, 'reps' => 12]))
            ->assertRedirect(route('workouts.show', $this->workout))
            ->assertSessionHasNoErrors();

        $this->assertSame(12, $set->fresh()->reps);
    }

    public function test_a_set_can_be_deleted(): void
    {
        $set = WorkoutSet::factory()->for($this->workout)->create();

        $this->delete(route('sets.destroy', $set))->assertRedirect(route('workouts.show', $this->workout));

        $this->assertModelMissing($set);
    }
}
