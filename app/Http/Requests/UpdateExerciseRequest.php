<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateExerciseRequest extends StoreExerciseRequest
{
    /**
     * Same rules as creating, but the exercise may keep its own name.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => ['required', 'string', 'max:100', Rule::unique('exercises', 'name')->ignore($this->route('exercise'))],
        ]);
    }
}
