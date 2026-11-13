<?php

namespace Tests\Feature;

use App\Jobs\NotifyPersonalRecord;
use App\Mail\PersonalRecordMail;
use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PersonalRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_heavier_set_becomes_a_personal_record_and_queues_a_job(): void
    {
        Queue::fake(); // record pushed jobs instead of running them
        $user = $this->signIn();
        $squat = Exercise::factory()->create();
        $lastWeek = Workout::factory()->for($user)->create(['date' => today()->subWeek()]);
        $today = Workout::factory()->for($user)->create(['date' => today()]);
        $log = fn (Workout $w, int $nr, float $kg) => $this->post(route('workouts.sets.store', $w),
            ['exercise_id' => $squat->id, 'set_number' => $nr, 'reps' => 5, 'weight_kg' => $kg]);

        $log($lastWeek, 1, 100); // first time ever: nothing to beat
        $log($today, 1, 100);    // equal: not a record
        $log($today, 2, 105)->assertSessionHas('status', fn ($s) => str_contains($s, 'personal record'));
        $log($today, 3, 105);    // equal to the new best: not a record

        $this->assertSame([false, false, true, false], WorkoutSet::orderBy('id')->pluck('is_pr')->all());
        Queue::assertPushed(NotifyPersonalRecord::class, 1);
    }

    public function test_other_athletes_sets_do_not_count(): void
    {
        Queue::fake();
        $squat = Exercise::factory()->create();
        WorkoutSet::factory()->for($squat)->for(Workout::factory()->create(['date' => today()->subDay()]))
            ->create(['weight_kg' => 200]); // a stronger stranger
        $mine = Workout::factory()->create(['date' => today()]);
        WorkoutSet::factory()->for($squat)->for(Workout::factory()->for($mine->user)->create(['date' => today()->subWeek()]))
            ->create(['weight_kg' => 50]);

        $set = WorkoutSet::factory()->for($squat)->for($mine)->create(['weight_kg' => 60]);

        $this->assertTrue($set->is_pr);
    }

    public function test_the_job_emails_the_athlete(): void
    {
        Mail::fake();
        $set = WorkoutSet::factory()->create();

        (new NotifyPersonalRecord($set))->handle();

        Mail::assertSent(PersonalRecordMail::class, fn ($mail) => $mail->hasTo($set->workout->user->email));
    }
}
