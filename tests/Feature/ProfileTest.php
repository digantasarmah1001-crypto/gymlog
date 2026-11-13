<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_profile_shows_stats_but_never_email_or_bodyweight(): void
    {
        $set = WorkoutSet::factory()->create(['weight_kg' => 100]);
        $athlete = $set->workout->user;
        $athlete->update(['bodyweight_kg' => 81.5]);

        $this->get(route('profiles.index'))->assertOk()->assertSee($athlete->name);
        $this->get(route('profiles.show', $athlete))->assertOk()
            ->assertSee($set->exercise->name)
            ->assertSee('100 kg')
            ->assertDontSee($athlete->email)
            ->assertDontSee('81.5');
    }

    public function test_you_see_your_own_bodyweight(): void
    {
        $me = $this->signIn(User::factory()->create(['bodyweight_kg' => 81.5]));

        $this->get(route('profiles.show', $me))->assertSee('81.5 kg');
    }

    public function test_you_can_edit_your_own_profile(): void
    {
        $me = $this->signIn();

        $this->get(route('profile.edit'))->assertOk();
        $this->put(route('profile.update'), ['name' => 'New Name', 'goal' => 'Squat 140 kg', 'bodyweight_kg' => 80])
            ->assertRedirect(route('profiles.show', $me));

        $this->assertSame('Squat 140 kg', $me->fresh()->goal);
    }

    public function test_profile_input_is_validated(): void
    {
        $this->signIn();

        $this->put(route('profile.update'), ['name' => '', 'bodyweight_kg' => 5])
            ->assertSessionHasErrors(['name', 'bodyweight_kg']);
    }
}
