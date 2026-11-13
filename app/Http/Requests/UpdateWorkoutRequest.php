<?php

namespace App\Http\Requests;

/**
 * Updating a workout uses the same rules as creating one, but only the owner (or an admin) may do it.
 */
class UpdateWorkoutRequest extends StoreWorkoutRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('workout'));
    }
}
