<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Serves PRIVATE progress photos. The file lives in storage/app/private, which has no public URL,
 * so the only way to see it is through this route, and it checks the permission first.
 */
class WorkoutPhotoController extends Controller
{
    public function show(Workout $workout): StreamedResponse
    {
        Gate::authorize('view', $workout);         // same rule as the workout: owner or admin

        abort_unless($workout->photo_path, 404);    // no photo uploaded

        return Storage::disk('local')->response($workout->photo_path);
    }
}
