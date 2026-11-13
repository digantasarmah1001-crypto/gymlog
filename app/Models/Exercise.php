<?php

namespace App\Models;

use Database\Factories\ExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'muscle_group', 'equipment', 'description'])]
class Exercise extends Model
{
    /** @use HasFactory<ExerciseFactory> */
    use HasFactory;

    public const MUSCLE_GROUPS = ['Chest', 'Back', 'Legs', 'Shoulders', 'Arms', 'Core', 'Full body'];

    /**
     * Every set ever logged for this exercise.
     */
    public function sets(): HasMany
    {
        return $this->hasMany(WorkoutSet::class);
    }
}
