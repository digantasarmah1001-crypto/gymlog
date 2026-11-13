{{--
    A labelled form field. old($name, $value) = what the user typed if the form came back
    with errors, otherwise the current value. Extra attributes (required, min…) pass through.
--}}
@props(['name', 'label', 'type' => 'text', 'value' => null])

<div @class(['field', 'has-error' => $errors->has($name)])>
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}" {{ $attributes }}>
    <x-error :name="$name" />
</div>
