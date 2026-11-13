<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Exercise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * The exercise library: everyone may browse it, only admins may change it (ExercisePolicy).
 */
class ExerciseController extends Controller implements HasMiddleware
{
    /**
     * Login is required for everything except browsing (index and show).
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

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
        Gate::authorize('create', Exercise::class);

        return view('exercises.create', ['exercise' => new Exercise]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExerciseRequest $request): RedirectResponse
    {
        $exercise = new Exercise($request->safe()->except('image'));

        if ($request->hasFile('image')) {
            // store() picks a unique random file name and returns the path, e.g. "exercises/Xy7….jpg"
            $exercise->image_path = $request->file('image')->store('exercises', 'public');
        }

        $exercise->save();

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
        Gate::authorize('update', $exercise);

        return view('exercises.edit', ['exercise' => $exercise]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExerciseRequest $request, Exercise $exercise): RedirectResponse
    {
        $exercise->fill($request->safe()->except('image'));

        if ($request->hasFile('image')) {
            // Replace the old photo: delete the old file first so no orphans stay on disk.
            if ($exercise->image_path) {
                Storage::disk('public')->delete($exercise->image_path);
            }
            $exercise->image_path = $request->file('image')->store('exercises', 'public');
        }

        $exercise->save();

        return redirect()->route('exercises.show', $exercise)
            ->with('status', 'Exercise updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise): RedirectResponse
    {
        Gate::authorize('delete', $exercise);

        if ($exercise->image_path) {
            Storage::disk('public')->delete($exercise->image_path);
        }
        $exercise->delete();

        return redirect()->route('exercises.index')
            ->with('status', "Exercise \"{$exercise->name}\" deleted.");
    }
}
