<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // you can only ever edit your OWN profile (the controller uses $request->user())
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'goal' => ['nullable', 'string', 'max:120'],
            'bodyweight_kg' => ['nullable', 'numeric', 'min:30', 'max:300', 'decimal:0,1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['bodyweight_kg' => 'bodyweight'];
    }
}
