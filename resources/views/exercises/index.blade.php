@extends('layouts.app')

@section('title', 'Exercises')

@section('content')
    <div class="page-header">
        <h1>Exercises</h1>
        @can('create', App\Models\Exercise::class)
            <a href="{{ route('exercises.create') }}" class="btn btn-primary">+ New exercise</a>
        @endcan
    </div>

    <div class="card">
        <table>
            <thead>
                <tr><th>Name</th><th>Muscle group</th><th>Equipment</th><th>Sets logged</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($exercises as $exercise)
                    <tr>
                        <td>
                            @if ($exercise->image_path)
                                <img src="{{ $exercise->imageUrl() }}" alt="" class="thumb thumb-sm">
                            @endif
                            <a href="{{ route('exercises.show', $exercise) }}">{{ $exercise->name }}</a>
                        </td>
                        <td><span class="badge">{{ $exercise->muscle_group }}</span></td>
                        <td>{{ $exercise->equipment ?? '—' }}</td>
                        <td>{{ $exercise->sets_count }}</td>
                        <td class="actions">
                            @can('update', $exercise)
                                <a href="{{ route('exercises.edit', $exercise) }}" class="btn btn-sm">Edit</a>
                            @endcan
                            @can('delete', $exercise)
                                <x-delete-button :action="route('exercises.destroy', $exercise)"
                                                 confirm="Delete this exercise?" />
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">No exercises yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $exercises->links() }}
    </div>
@endsection
