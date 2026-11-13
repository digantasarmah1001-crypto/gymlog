<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkoutSet;
use Illuminate\View\View;

/**
 * Athlete profiles with training stats.
 *
 * Two ways to calculate things, on purpose:
 *  - Eloquent / SQL: the DATABASE does the work and only sends back the answer (fast, little memory).
 *  - Collections:    we load rows into PHP first, then group/sum them in PHP (flexible, easy to read).
 */
class ProfileController extends Controller
{
    /**
     * List all athletes with how many workouts they logged.
     */
    public function index(): View
    {
        return view('profiles.index', [
            // withCount adds a "workouts_count" column, calculated by the database.
            'athletes' => User::withCount('workouts')->orderByDesc('workouts_count')->orderBy('name')->get(),
        ]);
    }

    /**
     * One athlete's profile and stats.
     */
    public function show(User $user): View
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

        return view('profiles.show', [
            'athlete' => $user,
            'totalWorkouts' => $totalWorkouts,
            'workoutsThisMonth' => $workoutsThisMonth,
            'totalSets' => $sets->count(),
            'favouriteExercise' => $favouriteExercise,
            'personalBests' => $personalBests,
            'volumeByMuscle' => $volumeByMuscle,
            'recentWorkouts' => $user->workouts()->withCount('sets')->latest('date')->take(5)->get(),
        ]);
    }
}
