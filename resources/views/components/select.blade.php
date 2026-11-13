{{-- A labelled dropdown. $options = [value => label, ...]. The old/current value is pre-selected. --}}
@props(['name', 'label', 'options', 'value' => null, 'placeholder' => 'Choose…'])

<div @class(['field', 'has-error' => $errors->has($name)])>
    <label for="{{ $name }}">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" {{ $attributes }}>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    <x-error :name="$name" />
</div>
