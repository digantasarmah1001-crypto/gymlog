{{-- Shows the validation error for one field, if there is one. --}}
@props(['name'])

@error($name)
    <div class="error">{{ $message }}</div>
@enderror
