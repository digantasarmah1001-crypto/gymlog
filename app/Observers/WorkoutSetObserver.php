<?php

namespace App\Observers;

use App\Http\Controllers\HomeController;
use App\Jobs\NotifyPersonalRecord;
use App\Models\WorkoutSet;
use Illuminate\Support\Facades\Cache;

/**
 * Model events for WorkoutSet. Laravel calls these methods by itself
 * (registered with #[ObservedBy] on the model), whatever code saves the set.
 */
class WorkoutSetObserver
{
    /**
     * A new set was saved: check for a personal record.
     */
    public function created(WorkoutSet $set): void
    {
        if ($set->beatsPreviousBest()) {
            $set->is_pr = true;
            $set->saveQuietly(); // save WITHOUT firing events again (otherwise "updated" would run)

            // Don't send the email now: put a job on the queue. A worker (php artisan queue:work)
            // sends it in the background, so the athlete doesn't wait for the mail server.
            NotifyPersonalRecord::dispatch($set);
        }

        $this->forgetHomeStats();
    }

    /**
     * An existing set was edited: the weight may have changed, so recheck the record (no email).
     */
    public function updated(WorkoutSet $set): void
    {
        if ($set->wasChanged('weight_kg')) {
            $set->is_pr = $set->beatsPreviousBest();
            $set->saveQuietly();
        }

        $this->forgetHomeStats();
    }

    public function deleted(WorkoutSet $set): void
    {
        $this->forgetHomeStats();
    }

    /**
     * The welcome-page totals are cached (session 11): throw them away so they are recalculated.
     */
    private function forgetHomeStats(): void
    {
        Cache::forget(HomeController::STATS_CACHE_KEY);
    }
}
