<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Cache keys used on this page (also cleared elsewhere when the data changes).
     */
    public const STATS_CACHE_KEY = 'home.stats';

    public const EXERCISES_CACHE_KEY = 'home.exercises';

    /**
     * Welcome page: what GymLog is, some totals, your latest workouts and the exercise library.
     */
    public function index(): View
    {
        // Cache::remember(key, lifetime, function): if the key is in the cache, return it;
        // otherwise run the function (the slow queries), store the result and return it.
        // These totals read EVERY set in the database, so we don't want to do that on every visit.
        $stats = Cache::remember(self::STATS_CACHE_KEY, now()->addMinutes(10), fn () => [
            'workoutCount' => Workout::count(),
            'setCount' => WorkoutSet::count(),
            // Total volume = sum of (reps × weight) over every set, calculated by the database.
            'kgLifted' => (int) WorkoutSet::sum(DB::raw('reps * weight_kg')),
            'exerciseCount' => Exercise::count(),
        ]);

        // A collection grouped by muscle group: ['Chest' => [...], 'Legs' => [...]].
        $exercisesByMuscle = Cache::remember(self::EXERCISES_CACHE_KEY, now()->addMinutes(10),
            fn () => Exercise::orderBy('name')->get()->groupBy('muscle_group'));

        return view('welcome', $stats + [
            'exercisesByMuscle' => $exercisesByMuscle,

            // Personal data is never cached in a shared key: each athlete's own latest workouts.
            'latestWorkouts' => auth()->check()
                ? auth()->user()->workouts()->withCount('sets')->latest('date')->take(5)->get()
                : collect(),
        ]);
    }
}
