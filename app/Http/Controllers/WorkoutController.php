<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource, optionally filtered by athlete (?user=ID).
     */
    public function index(Request $request): View
    {
        $workouts = Workout::with('user')
            ->withCount('sets')
            ->when($request->integer('user'), fn ($query, $userId) => $query->where('user_id', $userId))
            ->latest('date')
            ->paginate(15)
            ->withQueryString();

        return view('workouts.index', [
            'workouts' => $workouts,
            'athletes' => User::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('workouts.create', [
            'workout' => new Workout(['date' => today()]),
            'athletes' => User::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkoutRequest $request): RedirectResponse
    {
        $workout = Workout::create($request->validated());

        return redirect()->route('workouts.show', $workout)
            ->with('status', 'Workout logged. Now add your sets!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workout $workout): View
    {
        $workout->load(['user', 'sets.exercise']);

        return view('workouts.show', [
            'workout' => $workout,
            'totalVolume' => $workout->sets->sum(fn ($set) => $set->reps * $set->weight_kg),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workout $workout): View
    {
        return view('workouts.edit', [
            'workout' => $workout,
            'athletes' => User::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkoutRequest $request, Workout $workout): RedirectResponse
    {
        $workout->update($request->validated());

        return redirect()->route('workouts.show', $workout)
            ->with('status', 'Workout updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workout $workout): RedirectResponse
    {
        $workout->delete();

        return redirect()->route('workouts.index')
            ->with('status', "Workout \"{$workout->title}\" deleted.");
    }
}
