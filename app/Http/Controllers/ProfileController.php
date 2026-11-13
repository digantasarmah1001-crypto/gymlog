<?php

namespace App\Http\Controllers;

use App\Actions\CalculateAthleteStats;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Athlete profiles: list, one profile (stats via CalculateAthleteStats), edit your own profile.
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
     * One athlete's profile and stats. The calculations live in an Action class (skinny controller).
     */
    public function show(User $user, CalculateAthleteStats $stats): View
    {
        return view('profiles.show', ['athlete' => $user] + $stats->handle($user));
    }

    /**
     * Edit YOUR OWN profile. There is no {user} in the URL, so you can't even try to edit someone else's.
     */
    public function edit(Request $request): View
    {
        return view('profiles.edit', ['athlete' => $request->user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()->route('profiles.show', $request->user())
            ->with('status', 'Profile updated.');
    }
}
