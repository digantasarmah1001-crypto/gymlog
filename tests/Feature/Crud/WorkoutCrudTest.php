<?php

namespace Tests\Feature\Crud;

use App\Models\Tag;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkoutCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_pages_render_for_the_owner(): void
    {
        $user = $this->signIn();
        $workout = Workout::factory()->for($user)->create(['title' => 'Heavy leg day']);
        WorkoutSet::factory()->for($workout)->create();

        $this->get(route('workouts.index'))->assertOk()->assertSee('Heavy leg day');
        $this->get(route('workouts.show', $workout))->assertOk()->assertSee('Heavy leg day');
        $this->get(route('workouts.create'))->assertOk();
        $this->get(route('workouts.edit', $workout))->assertOk()->assertSee('Heavy leg day');
    }

    public function test_a_workout_is_logged_for_the_signed_in_athlete(): void
    {
        $user = $this->signIn();

        $this->post(route('workouts.store'), ['title' => 'Push day', 'date' => today()->toDateString(), 'duration_minutes' => 60])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('workouts', ['user_id' => $user->id, 'title' => 'Push day']);
    }

    public function test_invalid_input_is_rejected(): void
    {
        $this->signIn();

        $this->from(route('workouts.create'))
            ->post(route('workouts.store'), ['title' => '', 'date' => today()->addDay()->toDateString(), 'duration_minutes' => 0])
            ->assertRedirect(route('workouts.create'))
            ->assertSessionHasErrors(['title', 'date', 'duration_minutes']);

        $this->assertDatabaseCount('workouts', 0);
    }

    public function test_a_workout_can_be_updated_with_tags(): void
    {
        $user = $this->signIn();
        $workout = Workout::factory()->for($user)->create();
        [$heavy, $pr] = Tag::factory(2)->create();

        $this->put(route('workouts.update', $workout), [
            'title' => 'Renamed', 'date' => $workout->date->toDateString(), 'tags' => [$heavy->id, $pr->id],
        ])->assertRedirect(route('workouts.show', $workout));

        $this->assertSame('Renamed', $workout->fresh()->title);
        $this->assertCount(2, $workout->tags);

        // Unticking all tags removes the links (sync), the tags themselves stay.
        $this->put(route('workouts.update', $workout), ['title' => 'Renamed', 'date' => $workout->date->toDateString()]);
        $this->assertCount(0, $workout->fresh()->tags);
        $this->assertDatabaseCount('tags', 2);
    }

    public function test_deleting_a_workout_deletes_its_sets(): void
    {
        $user = $this->signIn();
        $workout = Workout::factory()->for($user)->create();
        WorkoutSet::factory()->for($workout)->create();

        $this->delete(route('workouts.destroy', $workout))->assertRedirect(route('workouts.index'));

        $this->assertModelMissing($workout);
        $this->assertDatabaseCount('workout_sets', 0); // cascadeOnDelete
    }
}
