<?php

namespace App\Models;

use App\Http\Controllers\HomeController;
use Database\Factories\ExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'muscle_group', 'equipment', 'description'])]
class Exercise extends Model
{
    /** @use HasFactory<ExerciseFactory> */
    use HasFactory;

    /**
     * Model events written as closures (another way than an observer class):
     * when a exercise is saved or deleted, the cached welcome-page exercise list and count is outdated.
     */
    protected static function booted(): void
    {
        $forget = function () {
            Cache::forget(HomeController::STATS_CACHE_KEY);
            Cache::forget(HomeController::EXERCISES_CACHE_KEY);
        };

        static::saved($forget);
        static::deleted($forget);
    }

    public const MUSCLE_GROUPS = ['Chest', 'Back', 'Legs', 'Shoulders', 'Arms', 'Core', 'Full body'];

    /**
     * Every set ever logged for this exercise.
     */
    public function sets(): HasMany
    {
        return $this->hasMany(WorkoutSet::class);
    }

    /**
     * Public URL of the photo, or null. asset() uses the address you're browsing on,
     * so it also works with "php artisan serve" on port 8000. Needs: php artisan storage:link
     */
    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }
}
