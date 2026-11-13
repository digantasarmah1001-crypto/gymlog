<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Exercise;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('exercises.index', [
            'exercises' => Exercise::withCount('sets')->orderBy('muscle_group')->orderBy('name')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('exercises.create', ['exercise' => new Exercise]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExerciseRequest $request): RedirectResponse
    {
        $exercise = Exercise::create($request->validated());

        return redirect()->route('exercises.show', $exercise)
            ->with('status', "Exercise \"{$exercise->name}\" added.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise): View
    {
        return view('exercises.show', [
            'exercise' => $exercise,
            'heaviestSet' => $exercise->sets()->orderByDesc('weight_kg')->first(),
            'recentSets' => $exercise->sets()
                ->with('workout.user')
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exercise $exercise): View
    {
        return view('exercises.edit', ['exercise' => $exercise]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExerciseRequest $request, Exercise $exercise): RedirectResponse
    {
        $exercise->update($request->validated());

        return redirect()->route('exercises.show', $exercise)
            ->with('status', 'Exercise updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise): RedirectResponse
    {
        $exercise->delete();

        return redirect()->route('exercises.index')
            ->with('status', "Exercise \"{$exercise->name}\" deleted.");
    }
}
