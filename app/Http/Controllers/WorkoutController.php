<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use App\Models\Tag;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Workouts are private. Every route here requires login (see routes/web.php),
 * and WorkoutPolicy decides who may see or change a single workout.
 */
class WorkoutController extends Controller
{
    /**
     * Athletes see their own workouts. Admins see everyone's and can filter by athlete (?user=ID).
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $workouts = Workout::with(['user', 'tags'])
            ->withCount('sets')
            ->when(! $user->is_admin, fn ($query) => $query->where('user_id', $user->id))
            ->when($user->is_admin && $request->integer('user'), fn ($query) => $query->where('user_id', $request->integer('user')))
            ->latest('date')
            ->paginate(15)
            ->withQueryString();

        return view('workouts.index', [
            'workouts' => $workouts,
            'athletes' => $user->is_admin ? User::orderBy('name')->pluck('name', 'id') : collect(),
        ]);
    }

    public function create(): View
    {
        return view('workouts.create', [
            'workout' => new Workout(['date' => today()]),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    /**
     * The new workout always belongs to the logged-in athlete (not to an id sent by the form).
     */
    public function store(StoreWorkoutRequest $request): RedirectResponse
    {
        // 'tags' and 'photo' aren't columns, so save the other fields first.
        $workout = $request->user()->workouts()->make($request->safe()->except(['tags', 'photo']));

        if ($request->hasFile('photo')) {
            // 'local' = the PRIVATE disk (storage/app/private): no public URL exists for it.
            $workout->photo_path = $request->file('photo')->store('progress-photos', 'local')
                ?: throw ValidationException::withMessages(['photo' => 'The photo could not be saved, please try again.']);
        }

        $workout->save();
        $workout->tags()->sync($request->validated('tags', []));  // then the N-M links

        return redirect()->route('workouts.show', $workout)
            ->with('status', 'Workout logged. Now add your sets!');
    }

    public function show(Workout $workout): View
    {
        Gate::authorize('view', $workout); // 403 if it isn't your workout

        $workout->load(['user', 'tags', 'sets.exercise']);

        return view('workouts.show', [
            'workout' => $workout,
            'totalVolume' => $workout->sets->sum(fn ($set) => $set->reps * $set->weight_kg),
        ]);
    }

    public function edit(Workout $workout): View
    {
        Gate::authorize('update', $workout);

        return view('workouts.edit', [
            'workout' => $workout,
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    /**
     * UpdateWorkoutRequest already checked the permission (authorize()) and the input (rules()).
     */
    public function update(UpdateWorkoutRequest $request, Workout $workout): RedirectResponse
    {
        $workout->fill($request->safe()->except(['tags', 'photo']));

        if ($request->hasFile('photo')) {
            if ($workout->photo_path) {
                Storage::disk('local')->delete($workout->photo_path);
            }
            $workout->photo_path = $request->file('photo')->store('progress-photos', 'local')
                ?: throw ValidationException::withMessages(['photo' => 'The photo could not be saved, please try again.']);
        }

        $workout->save();
        // sync() = make the pivot table match exactly these tag ids (adds new links, removes unticked ones).
        $workout->tags()->sync($request->validated('tags', []));

        return redirect()->route('workouts.show', $workout)
            ->with('status', 'Workout updated.');
    }

    public function destroy(Workout $workout): RedirectResponse
    {
        Gate::authorize('delete', $workout);

        if ($workout->photo_path) {
            Storage::disk('local')->delete($workout->photo_path);
        }
        $workout->delete();

        return redirect()->route('workouts.index')
            ->with('status', "Workout \"{$workout->title}\" deleted.");
    }
}
