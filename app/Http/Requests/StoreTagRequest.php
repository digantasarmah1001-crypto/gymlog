<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
{
    /**
     * Only admins may add tags (TagPolicy@create).
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Tag::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:40', Rule::unique('tags', 'name')->ignore($this->route('tag'))],
        ];
    }
}
