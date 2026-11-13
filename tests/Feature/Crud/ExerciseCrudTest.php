<?php

namespace Tests\Feature\Crud;

use App\Models\Exercise;
use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature test: sends real HTTP requests to the app and checks the response and the database.
 * RefreshDatabase = every test starts with an empty, freshly migrated (in-memory) database.
 */
class ExerciseCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signInAsAdmin(); // only admins manage exercises
    }

    public function test_all_pages_render(): void
    {
        $exercise = Exercise::factory()->create();

        $this->get(route('exercises.index'))->assertOk()->assertSee($exercise->name);
        $this->get(route('exercises.show', $exercise))->assertOk()->assertSee($exercise->name);
        $this->get(route('exercises.create'))->assertOk();
        $this->get(route('exercises.edit', $exercise))->assertOk()->assertSee($exercise->name);
    }

    public function test_an_exercise_can_be_created(): void
    {
        $this->post(route('exercises.store'), ['name' => 'Zercher Squat', 'muscle_group' => 'Legs', 'equipment' => 'Barbell'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('exercises', ['name' => 'Zercher Squat', 'muscle_group' => 'Legs']);
    }

    public function test_invalid_input_is_rejected(): void
    {
        $existing = Exercise::factory()->create();

        $this->from(route('exercises.create'))
            ->post(route('exercises.store'), ['name' => $existing->name, 'muscle_group' => 'Toes'])
            ->assertRedirect(route('exercises.create'))           // back to the form...
            ->assertSessionHasErrors(['name', 'muscle_group']);   // ...with an error per field

        $this->assertDatabaseCount('exercises', 1);
    }

    public function test_an_exercise_can_be_updated_and_keep_its_own_name(): void
    {
        $exercise = Exercise::factory()->create(['muscle_group' => 'Chest']);

        $this->put(route('exercises.update', $exercise), ['name' => $exercise->name, 'muscle_group' => 'Back'])
            ->assertRedirect(route('exercises.show', $exercise))
            ->assertSessionHasNoErrors();

        $this->assertSame('Back', $exercise->fresh()->muscle_group);
    }

    public function test_an_unused_exercise_can_be_deleted(): void
    {
        $exercise = Exercise::factory()->create();

        $this->delete(route('exercises.destroy', $exercise))->assertRedirect(route('exercises.index'));

        $this->assertModelMissing($exercise);
    }

    public function test_an_exercise_with_logged_sets_cannot_be_deleted(): void
    {
        $exercise = WorkoutSet::factory()->create()->exercise;

        $this->delete(route('exercises.destroy', $exercise))
            ->assertRedirect(route('exercises.show', $exercise))
            ->assertSessionHas('error');                           // ExerciseInUseException

        $this->assertModelExists($exercise);
    }
}
