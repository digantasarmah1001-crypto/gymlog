<?php

namespace App\Exceptions;

use App\Models\Exercise;
use Exception;
use Illuminate\Http\RedirectResponse;

/**
 * Thrown when someone tries to delete an exercise that athletes already logged sets for.
 * Deleting it would (via cascadeOnDelete) silently wipe those sets from everyone's history.
 */
class ExerciseInUseException extends Exception
{
    public function __construct(public Exercise $exercise)
    {
        $count = $exercise->sets()->count();

        parent::__construct("\"{$exercise->name}\" can't be deleted: it is used in {$count} logged sets.");
    }

    /**
     * Laravel calls render() automatically when this exception isn't caught:
     * instead of an error page, the user goes back to the exercise with a red message.
     */
    public function render(): RedirectResponse
    {
        return redirect()->route('exercises.show', $this->exercise)->with('error', $this->getMessage());
    }

    /**
     * This is an expected situation, not a bug: don't write it to the error log.
     */
    public function report(): bool
    {
        return true; // true = "handled, don't log"
    }
}
