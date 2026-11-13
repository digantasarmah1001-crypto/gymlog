<?php

namespace App\Models;

use Database\Factories\WorkoutSetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['workout_id', 'exercise_id', 'set_number', 'reps', 'weight_kg'])]
class WorkoutSet extends Model
{
    /** @use HasFactory<WorkoutSetFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
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
}
