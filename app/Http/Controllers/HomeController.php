<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Welcome page: what GymLog is, some totals, the latest workouts and the exercise library.
     */
    public function index(): View
    {
        return view('welcome', [
            'workoutCount' => Workout::count(),
            'setCount' => WorkoutSet::count(),
            // Total volume = sum of (reps × weight) over every set, calculated by the database.
            'kgLifted' => (int) WorkoutSet::sum(DB::raw('reps * weight_kg')),
            'exerciseCount' => Exercise::count(),

            // with('user') loads the users in one extra query instead of one per workout (avoids N+1).
            'latestWorkouts' => Workout::with('user')->withCount('sets')->latest('date')->take(5)->get(),

            // A collection grouped by muscle group: ['Chest' => [...], 'Legs' => [...]].
            'exercisesByMuscle' => Exercise::orderBy('name')->get()->groupBy('muscle_group'),
        ]);
    }
}
