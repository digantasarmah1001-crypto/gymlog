{{-- A big number with a small label underneath. Usage: <x-stat label="Workouts" :value="12" /> --}}
@props(['label', 'value'])

<div class="stat">
    <div class="value">{{ $value }}</div>
    <div class="label">{{ $label }}</div>
</div>
