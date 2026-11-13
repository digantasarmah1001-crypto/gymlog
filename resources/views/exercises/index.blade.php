@extends('layouts.app')

@section('title', 'Exercises')

@section('content')
    <div class="page-header">
        <h1>Exercises</h1>
        <a href="{{ route('exercises.create') }}" class="btn btn-primary">+ New exercise</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr><th>Name</th><th>Muscle group</th><th>Equipment</th><th>Sets logged</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($exercises as $exercise)
                    <tr>
                        <td><a href="{{ route('exercises.show', $exercise) }}">{{ $exercise->name }}</a></td>
                        <td><span class="badge">{{ $exercise->muscle_group }}</span></td>
                        <td>{{ $exercise->equipment ?? '—' }}</td>
                        <td>{{ $exercise->sets_count }}</td>
                        <td class="actions">
                            <a href="{{ route('exercises.edit', $exercise) }}" class="btn btn-sm">Edit</a>
                            <x-delete-button :action="route('exercises.destroy', $exercise)"
                                             confirm="Delete this exercise and all its logged sets?" />
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
