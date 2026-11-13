<?php

namespace App\Actions;

use App\Models\WorkoutSet;

/**
 * Go through ALL sets, oldest first, and set is_pr again.
 * Normally WorkoutSetObserver does this one set at a time; this action is for bulk situations:
 * after seeding (model events are off there) or via `php artisan gymlog:recalculate-records`.
 */
class RecalculatePersonalRecords
{
    /**
     * @return int how many sets are a personal record
     */
    public function handle(): int
    {
        $records = 0;

        WorkoutSet::with('workout')->get()
            ->sortBy(fn (WorkoutSet $set) => [$set->workout->date, $set->set_number])
            ->each(function (WorkoutSet $set) use (&$records) {
                $set->is_pr = $set->beatsPreviousBest();
                $set->saveQuietly(); // no model events: we don't want an email for every old record
                $records += (int) $set->is_pr;
            });

        return $records;
    }
}
