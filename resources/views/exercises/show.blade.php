@extends('layouts.app')

@section('title', $exercise->name)

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $exercise->name }}</h1>
            <span class="badge">{{ $exercise->muscle_group }}</span>
            <span class="muted">{{ $exercise->equipment }}</span>
        </div>
        <div class="actions">
            @can('update', $exercise)
                <a href="{{ route('exercises.edit', $exercise) }}" class="btn">Edit</a>
            @endcan
            @can('delete', $exercise)
                <x-delete-button :action="route('exercises.destroy', $exercise)"
                                 confirm="Delete this exercise and all its logged sets?" />
            @endcan
        </div>
    </div>

    @if ($exercise->description)
        <div class="card">{{ $exercise->description }}</div>
    @endif

    <div class="card">
        <h2>Heaviest set</h2>
        @if ($heaviestSet)
            <p><strong>{{ $heaviestSet->weight_kg }} kg</strong> × {{ $heaviestSet->reps }} reps</p>
        @else
            <p class="muted">Nobody has logged this exercise yet.</p>
        @endif
    </div>

    <div class="card">
        <h2>Recently logged</h2>
        <table>
            <thead><tr><th>Date</th><th>Athlete</th><th>Workout</th><th>Reps</th><th>Weight</th></tr></thead>
            <tbody>
                @forelse ($recentSets as $set)
                    <tr>
                        <td>{{ $set->workout->date->format('j M Y') }}</td>
                        <td><a href="{{ route('profiles.show', $set->workout->user) }}">{{ $set->workout->user->name }}</a></td>
                        <td>{{ $set->workout->title }}</td>
                        <td>{{ $set->reps }}</td>
                        <td>{{ $set->weight_kg }} kg</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">No sets logged yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('exercises.index') }}">← All exercises</a>
@endsection
