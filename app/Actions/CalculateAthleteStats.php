<?php

namespace App\Actions;

use App\Models\User;
use App\Models\WorkoutSet;

/**
 * All training statistics for one athlete's profile page.
 *
 * Two ways to calculate things, on purpose:
 *  - Eloquent / SQL: the DATABASE does the work and only sends back the answer (fast, little memory).
 *  - Collections:    we load rows into PHP first, then group/sum them in PHP (flexible, easy to read).
 */
class CalculateAthleteStats
{
    /**
     * @return array<string, mixed>
     */
    public function handle(User $user): array
    {
        // ---------- Eloquent: let the database count ----------
        $totalWorkouts = $user->workouts()->count();
        $workoutsThisMonth = $user->workouts()
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        // Personal best per exercise: the database groups the sets by exercise and keeps the MAX weight.
        $personalBests = WorkoutSet::query()
            ->whereHas('workout', fn ($query) => $query->where('user_id', $user->id)) // only this user's sets
            ->selectRaw('exercise_id, MAX(weight_kg) as best_kg, COUNT(*) as times')
            ->groupBy('exercise_id')
            ->with('exercise')
            ->orderByDesc('best_kg')
            ->get();

        // ---------- Collections: load the sets once, then work in PHP ----------
        $sets = $user->sets()->with('exercise')->get();

        // Volume (reps × kg) per muscle group, biggest first: ['Legs' => 12500, 'Back' => 9800, ...]
        $volumeByMuscle = $sets
            ->groupBy(fn ($set) => $set->exercise->muscle_group)
            ->map(fn ($group) => $group->sum(fn ($set) => $set->reps * $set->weight_kg))
            ->sortDesc();

        // The exercise done most often.
        $favouriteExercise = $sets->countBy(fn ($set) => $set->exercise->name)->sortDesc()->keys()->first();

        return [
            'totalWorkouts' => $totalWorkouts,
            'workoutsThisMonth' => $workoutsThisMonth,
            'totalSets' => $sets->count(),
            'favouriteExercise' => $favouriteExercise,
            'personalBests' => $personalBests,
            'volumeByMuscle' => $volumeByMuscle,
            'recentWorkouts' => $user->workouts()->withCount('sets')->latest('date')->take(5)->get(),
        ];
    }
}
