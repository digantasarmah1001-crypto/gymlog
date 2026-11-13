<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_exercise_photo_is_stored_on_the_public_disk_and_replaced(): void
    {
        Storage::fake('public'); // a temporary fake disk, nothing is written to the real storage folder
        $this->signInAsAdmin();

        $this->post(route('exercises.store'), [
            'name' => 'Zercher Squat', 'muscle_group' => 'Legs',
            'image' => UploadedFile::fake()->image('squat.jpg'),
        ])->assertSessionHasNoErrors();
        $exercise = Exercise::first();
        $oldPath = $exercise->image_path;
        Storage::disk('public')->assertExists($oldPath);

        $this->put(route('exercises.update', $exercise), [
            'name' => 'Zercher Squat', 'muscle_group' => 'Legs',
            'image' => UploadedFile::fake()->image('better.png'),
        ]);

        Storage::disk('public')->assertMissing($oldPath);                     // old file cleaned up
        Storage::disk('public')->assertExists($exercise->fresh()->image_path);
    }

    public function test_only_small_images_are_accepted(): void
    {
        Storage::fake('public');
        $this->signInAsAdmin();

        $this->post(route('exercises.store'), ['name' => 'A', 'muscle_group' => 'Legs',
            'image' => UploadedFile::fake()->create('virus.php', 10, 'text/x-php')])->assertSessionHasErrors('image');
        $this->post(route('exercises.store'), ['name' => 'B', 'muscle_group' => 'Legs',
            'image' => UploadedFile::fake()->image('huge.jpg')->size(3000)])->assertSessionHasErrors('image');
    }

    public function test_progress_photos_are_private(): void
    {
        Storage::fake('local');
        $owner = $this->signIn();
        $this->post(route('workouts.store'), [
            'title' => 'Leg day', 'date' => today()->toDateString(),
            'photo' => UploadedFile::fake()->image('me.jpg'),
        ]);
        $workout = Workout::first();
        Storage::disk('local')->assertExists($workout->photo_path);

        $this->get(route('workouts.photo', $workout))->assertOk();                                   // owner
        $this->actingAs(User::factory()->create())->get(route('workouts.photo', $workout))->assertForbidden(); // stranger
        $this->actingAs(User::factory()->admin()->create())->get(route('workouts.photo', $workout))->assertOk(); // admin

        $this->actingAs($owner)->delete(route('workouts.destroy', $workout));
        Storage::disk('local')->assertMissing($workout->photo_path);                                  // file removed too
    }
}
