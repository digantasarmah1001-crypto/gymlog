<?php

namespace App\Models;

use Database\Factories\WorkoutFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'title', 'date', 'duration_minutes', 'notes'])]
class Workout extends Model
{
    /** @use HasFactory<WorkoutFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /**
     * The athlete who logged this workout.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The sets performed in this workout, in order.
     */
    public function sets(): HasMany
    {
        return $this->hasMany(WorkoutSet::class)->orderBy('set_number');
    }
}
