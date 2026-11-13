<?php

namespace Tests\Feature\Authorization;

use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Workouts (and their sets) are private: only the owner or an admin may see or change them.
 */
class WorkoutPrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_athletes_only_see_their_own_workouts_in_the_list(): void
    {
        $me = $this->signIn();
        Workout::factory()->for($me)->create(['title' => 'Mine']);
        Workout::factory()->create(['title' => 'Secret session']);

        $this->get(route('workouts.index'))->assertSee('Mine')->assertDontSee('Secret session');
    }

    public function test_someone_elses_workout_and_sets_are_forbidden(): void
    {
        $this->signIn();
        $theirs = Workout::factory()->create();
        $set = WorkoutSet::factory()->for($theirs)->create();

        $this->get(route('workouts.show', $theirs))->assertForbidden();
        $this->get(route('workouts.edit', $theirs))->assertForbidden();
        $this->put(route('workouts.update', $theirs), ['title' => 'Hacked', 'date' => today()->toDateString()])->assertForbidden();
        $this->delete(route('workouts.destroy', $theirs))->assertForbidden();
        $this->post(route('workouts.sets.store', $theirs), [])->assertForbidden();
        $this->get(route('sets.edit', $set))->assertForbidden();
        $this->delete(route('sets.destroy', $set))->assertForbidden();

        $this->assertModelExists($theirs);
        $this->assertModelExists($set);
    }

    public function test_user_id_in_the_form_is_ignored(): void
    {
        $me = $this->signIn();
        $other = User::factory()->create();

        $this->post(route('workouts.store'), ['user_id' => $other->id, 'title' => 'Mine', 'date' => today()->toDateString()]);

        $this->assertDatabaseHas('workouts', ['title' => 'Mine', 'user_id' => $me->id]);
    }

    public function test_admins_see_all_workouts(): void
    {
        $workout = Workout::factory()->create(['title' => 'Somebody else']);
        $this->signInAsAdmin();

        $this->get(route('workouts.index'))->assertSee('Somebody else');
        $this->get(route('workouts.show', $workout))->assertOk();
    }
}
