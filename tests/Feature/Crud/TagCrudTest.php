<?php

namespace Tests\Feature\Crud;

use App\Models\Tag;
use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_rename_and_delete_tags(): void
    {
        $this->signInAsAdmin();

        $this->get(route('tags.create'))->assertOk();
        $this->post(route('tags.store'), ['name' => 'PR'])->assertRedirect(route('tags.index'));
        $tag = Tag::firstWhere('name', 'PR');

        $this->get(route('tags.edit', $tag))->assertOk();
        $this->put(route('tags.update', $tag), ['name' => 'Personal record'])->assertRedirect();
        $this->assertSame('Personal record', $tag->fresh()->name);

        $this->delete(route('tags.destroy', $tag))->assertRedirect(route('tags.index'));
        $this->assertModelMissing($tag);
    }

    public function test_tag_names_are_unique(): void
    {
        $this->signInAsAdmin();
        Tag::factory()->create(['name' => 'PR']);

        $this->post(route('tags.store'), ['name' => 'PR'])->assertSessionHasErrors('name');
    }

    public function test_tag_page_only_lists_your_own_workouts(): void
    {
        $user = $this->signIn();
        $tag = Tag::factory()->create();
        Workout::factory()->for($user)->create(['title' => 'Mine'])->tags()->attach($tag);
        Workout::factory()->create(['title' => 'Someone else'])->tags()->attach($tag);

        $this->get(route('tags.index'))->assertOk()->assertSee($tag->name);
        $this->get(route('tags.show', $tag))->assertOk()->assertSee('Mine')->assertDontSee('Someone else');
    }
}
