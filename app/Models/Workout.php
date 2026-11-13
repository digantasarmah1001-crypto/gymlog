<?php

namespace App\Models;

use App\Http\Controllers\HomeController;
use Database\Factories\WorkoutFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'title', 'date', 'duration_minutes', 'notes'])]
class Workout extends Model
{
    /** @use HasFactory<WorkoutFactory> */
    use HasFactory;

    /**
     * Model events written as closures (another way than an observer class):
     * when a workout is saved or deleted, the cached welcome-page workout count is outdated.
     */
    protected static function booted(): void
    {
        $forget = function () {
            Cache::forget(HomeController::STATS_CACHE_KEY);
        };

        static::saved($forget);
        static::deleted($forget);
    }

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

    /**
     * N-M: the tags on this workout (pivot table tag_workout).
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->orderBy('name');
    }

    /**
     * Query scope: only the workouts this user may see (their own; an admin sees all).
     * Usage: Workout::visibleTo($user)->get()  or  $tag->workouts()->visibleTo($user)->get()
     * It is the "query" version of WorkoutPolicy@view, so both rules live next to the model.
     */
    #[Scope]
    protected function visibleTo(Builder $query, User $user): void
    {
        if (! $user->is_admin) {
            $query->where('user_id', $user->id);
        }
    }
}
