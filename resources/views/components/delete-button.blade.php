{{-- A delete button. data-confirm makes public/js/app.js ask "Are you sure?" first. --}}
@props(['action', 'label' => 'Delete', 'confirm' => 'Are you sure?'])

<form method="POST" action="{{ $action }}" class="inline" data-confirm="{{ $confirm }}">
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'btn btn-danger btn-sm']) }}>{{ $label }}</button>
</form>
