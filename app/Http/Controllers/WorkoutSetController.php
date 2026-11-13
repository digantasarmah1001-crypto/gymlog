<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkoutSetRequest;
use App\Http\Requests\UpdateWorkoutSetRequest;
use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Sets are a shallow nested resource:
 * /workouts/{workout}/sets (index, create, store) and /sets/{set} (show, edit, update, destroy).
 * WorkoutSetPolicy: you may only touch sets of your own workouts (admins: all).
 */
class WorkoutSetController extends Controller
{
    /**
     * Display all sets of a workout, grouped per exercise.
     */
    public function index(Workout $workout): View
    {
        Gate::authorize('view', $workout);

        $workout->load(['user', 'sets.exercise']);

        return view('sets.index', [
            'workout' => $workout,
            'setsByExercise' => $workout->sets->groupBy('exercise.name'),
        ]);
    }

    /**
     * Show the form for adding a set to a workout.
     */
    public function create(Workout $workout): View
    {
        Gate::authorize('update', $workout); // adding a set = changing the workout

        $lastSet = $workout->sets()->reorder()->latest('set_number')->first();

        return view('sets.create', [
            'workout' => $workout,
            // Pre-fill with the previous set so logging the next one is quick.
            'set' => new WorkoutSet([
                'set_number' => ($lastSet?->set_number ?? 0) + 1,
                'exercise_id' => $lastSet?->exercise_id,
                'reps' => $lastSet?->reps,
                'weight_kg' => $lastSet?->weight_kg,
            ]),
            'exercises' => $this->exerciseOptions(),
        ]);
    }

    /**
     * Store a newly created set for the workout.
     */
    public function store(StoreWorkoutSetRequest $request, Workout $workout): RedirectResponse
    {
        $set = $workout->sets()->create($request->validated());

        // is_pr was filled in by WorkoutSetObserver (model event) during create().
        return redirect()->route('workouts.show', $workout)
            ->with('status', $set->is_pr ? '🏆 New personal record! Set added.' : 'Set added.');
    }

    /**
     * Display the specified set.
     */
    public function show(WorkoutSet $set): View
    {
        Gate::authorize('view', $set);

        $set->load(['workout.user', 'exercise']);

        return view('sets.show', ['set' => $set]);
    }

    /**
     * Show the form for editing the specified set.
     */
    public function edit(WorkoutSet $set): View
    {
        Gate::authorize('update', $set);

        return view('sets.edit', [
            'set' => $set,
            'workout' => $set->workout,
            'exercises' => $this->exerciseOptions(),
        ]);
    }

    /**
     * Update the specified set.
     */
    public function update(UpdateWorkoutSetRequest $request, WorkoutSet $set): RedirectResponse
    {
        $set->update($request->validated());

        return redirect()->route('workouts.show', $set->workout_id)
            ->with('status', 'Set updated.');
    }

    /**
     * Remove the specified set.
     */
    public function destroy(WorkoutSet $set): RedirectResponse
    {
        Gate::authorize('delete', $set);

        $set->delete();

        return redirect()->route('workouts.show', $set->workout_id)
            ->with('status', 'Set deleted.');
    }

    /**
     * Exercises for the dropdown: id => "Name (Muscle group)".
     */
    private function exerciseOptions(): Collection
    {
        return Exercise::orderBy('name')->get()
            ->mapWithKeys(fn (Exercise $exercise) => [$exercise->id => "{$exercise->name} ({$exercise->muscle_group})"]);
    }
}
