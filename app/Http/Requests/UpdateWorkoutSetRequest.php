<?php

namespace App\Http\Requests;

/**
 * Updating a set uses the same rules as creating one
 * (the unique set number check ignores the set being edited).
 */
class UpdateWorkoutSetRequest extends StoreWorkoutSetRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('set'));
    }
}
