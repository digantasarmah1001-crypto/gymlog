{{-- Shows a weight (stored in kg) in the unit chosen in the session. Usage: <x-weight :kg="$set->weight_kg" /> --}}
@props(['kg', 'decimals' => 1])
{{ App\Support\Weight::format((float) $kg, null, $decimals) }}
