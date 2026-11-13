<?php

namespace Tests\Feature\Authorization;

use App\Models\Exercise;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The exercise library and the tag list are shared: everyone may read them, only admins change them.
 */
class AdminPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_browse_exercises_without_edit_buttons(): void
    {
        $exercise = Exercise::factory()->create();

        $this->get(route('exercises.index'))->assertOk()->assertSee($exercise->name);
        $this->get(route('exercises.show', $exercise))->assertOk()->assertDontSee('Edit');
    }

    public function test_athletes_cannot_change_exercises_or_tags(): void
    {
        $this->signIn();
        $exercise = Exercise::factory()->create();
        $tag = Tag::factory()->create();

        $this->get(route('exercises.create'))->assertForbidden();
        $this->post(route('exercises.store'), ['name' => 'X', 'muscle_group' => 'Legs'])->assertForbidden();
        $this->delete(route('exercises.destroy', $exercise))->assertForbidden();
        $this->post(route('tags.store'), ['name' => 'X'])->assertForbidden();
        $this->delete(route('tags.destroy', $tag))->assertForbidden();
    }

    public function test_nobody_can_make_themselves_admin(): void
    {
        $this->post('/register', [
            'name' => 'Eve', 'email' => 'eve@example.com',
            'password' => 'secret123', 'password_confirmation' => 'secret123',
            'is_admin' => 1, // sneaky extra field
        ]);
        $eve = User::firstWhere('email', 'eve@example.com');
        $this->assertFalse($eve->is_admin);

        $this->put(route('profile.update'), ['name' => 'Eve', 'is_admin' => 1]);
        $this->assertFalse($eve->fresh()->is_admin);
    }
}
