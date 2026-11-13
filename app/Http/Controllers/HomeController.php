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

            // Workouts are private: logged-in athletes see their OWN latest workouts, guests see none.
            'latestWorkouts' => auth()->check()
                ? auth()->user()->workouts()->withCount('sets')->latest('date')->take(5)->get()
                : collect(),

            // A collection grouped by muscle group: ['Chest' => [...], 'Legs' => [...]].
            'exercisesByMuscle' => Exercise::orderBy('name')->get()->groupBy('muscle_group'),
        ]);
    }
}
