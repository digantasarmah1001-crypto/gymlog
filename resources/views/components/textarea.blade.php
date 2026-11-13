{{-- A labelled multi-line text field (same idea as x-input). --}}
@props(['name', 'label', 'value' => null])

<div @class(['field', 'has-error' => $errors->has($name)])>
    <label for="{{ $name }}">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" {{ $attributes }}>{{ old($name, $value) }}</textarea>
    <x-error :name="$name" />
</div>
