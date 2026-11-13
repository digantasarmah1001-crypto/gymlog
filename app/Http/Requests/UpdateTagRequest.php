<?php

namespace App\Http\Requests;

/**
 * Same rules as creating (the unique check ignores the tag being edited), only admins may rename.
 */
class UpdateTagRequest extends StoreTagRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tag'));
    }
}
