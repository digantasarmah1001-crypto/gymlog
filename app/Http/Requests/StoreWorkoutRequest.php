<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * (Authorisation with policies is added in session 7.)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Temporary until session 7: the owner is picked in the form instead of auth()->id().
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Friendlier attribute names in error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'athlete',
            'duration_minutes' => 'duration',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.before_or_equal' => 'You can only log workouts that already happened.',
        ];
    }
}
