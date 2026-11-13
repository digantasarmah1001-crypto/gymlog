<?php

namespace App\Models;

use App\Observers\WorkoutSetObserver;
use Database\Factories\WorkoutSetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['workout_id', 'exercise_id', 'set_number', 'reps', 'weight_kg'])]
// Model events: WorkoutSetObserver runs automatically after a set is created, updated or deleted.
#[ObservedBy(WorkoutSetObserver::class)]
class WorkoutSet extends Model
{
    /** @use HasFactory<WorkoutSetFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
            'is_pr' => 'boolean',
        ];
    }

    /**
     * The workout this set belongs to.
     */
    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    /**
     * The exercise performed in this set.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Is this set heavier than every EARLIER set this athlete did for the same exercise?
     * "Earlier" = in an earlier workout, or earlier in this same workout (lower set number).
     * The very first time you do an exercise doesn't count as a record (nothing to beat yet).
     */
    public function beatsPreviousBest(): bool
    {
        $previousBest = self::query()
            ->where('exercise_id', $this->exercise_id)
            ->where(fn ($query) => $query
                // earlier in this same workout...
                ->where(fn ($same) => $same
                    ->where('workout_id', $this->workout_id)
                    ->where('set_number', '<', $this->set_number))
                // ...or in one of the athlete's earlier workouts
                ->orWhereHas('workout', fn ($earlier) => $earlier
                    ->where('user_id', $this->workout->user_id)
                    ->where('date', '<', $this->workout->date)))
            ->max('weight_kg');

        return $previousBest !== null && (float) $this->weight_kg > (float) $previousBest;
    }
}
