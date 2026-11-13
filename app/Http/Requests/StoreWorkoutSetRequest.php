<?php

namespace App\Http\Requests;

use App\Models\WorkoutSet;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkoutSetRequest extends FormRequest
{
    /**
     * Adding a set changes the workout, so you must be allowed to update that workout.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('workout'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'exercise_id' => ['required', 'integer', 'exists:exercises,id'],
            // A set number can only be used once within the same workout.
            'set_number' => [
                'required', 'integer', 'min:1', 'max:50',
                Rule::unique('workout_sets')
                    ->where('workout_id', $this->workoutId())
                    ->ignore($this->route('set')),
            ],
            'reps' => ['required', 'integer', 'min:1', 'max:100'],
            'weight_kg' => ['required', 'numeric', 'min:0', 'max:1000', 'decimal:0,2'],
        ];
    }

    /**
     * The workout this set belongs to: from the URL when creating, from the set when editing.
     */
    protected function workoutId(): ?int
    {
        $set = $this->route('set');

        return $set instanceof WorkoutSet ? $set->workout_id : $this->route('workout')?->id;
    }

    /**
     * Friendlier attribute names in error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'exercise_id' => 'exercise',
            'set_number' => 'set number',
            'weight_kg' => 'weight',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'set_number.unique' => 'This workout already has a set with that number.',
        ];
    }
}
