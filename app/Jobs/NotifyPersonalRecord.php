<?php

namespace App\Jobs;

use App\Mail\PersonalRecordMail;
use App\Models\WorkoutSet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

/**
 * Background job: email the athlete about their new personal record.
 * ShouldQueue = don't run now, store it in the "jobs" table; `php artisan queue:work` runs it later.
 */
class NotifyPersonalRecord implements ShouldQueue
{
    use Queueable;

    public int $tries = 3; // if sending fails (mail server down), try again up to 3 times

    /**
     * Only the set's id is stored in the queue; the worker loads the set fresh from the database.
     */
    public function __construct(public WorkoutSet $set)
    {
    }

    public function handle(): void
    {
        $this->set->load(['workout.user', 'exercise']);

        Mail::to($this->set->workout->user)->send(new PersonalRecordMail($this->set));
    }
}
