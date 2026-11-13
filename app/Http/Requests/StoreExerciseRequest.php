<?php

namespace App\Http\Requests;

use App\Models\Exercise;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExerciseRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100', Rule::unique('exercises', 'name')],
            'muscle_group' => ['required', Rule::in(Exercise::MUSCLE_GROUPS)],
            'equipment' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
