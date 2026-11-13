@props(['action', 'label' => 'Delete', 'confirm' => 'Are you sure?'])

<form method="POST" action="{{ $action }}" class="inline" onsubmit="return confirm(@js($confirm))">
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'btn btn-danger btn-sm']) }}>{{ $label }}</button>
</form>
