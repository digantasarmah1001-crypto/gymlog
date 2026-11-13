@extends('layouts.app')

@section('title', $workout->title)

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $workout->title }}</h1>
            <span class="muted">
                <a href="{{ route('profiles.show', $workout->user) }}">{{ $workout->user->name }}</a> · {{ $workout->date->format('l j F Y') }}
                @if ($workout->duration_minutes) · {{ $workout->duration_minutes }} min @endif
            </span>
        </div>
        <div class="actions">
            <a href="{{ route('workouts.edit', $workout) }}" class="btn">Edit</a>
            <x-delete-button :action="route('workouts.destroy', $workout)"
                             confirm="Delete this workout and all of its sets?" />
        </div>
    </div>

    @if ($workout->tags->isNotEmpty())
        <p>
            @foreach ($workout->tags as $tag)
                <a href="{{ route('tags.show', $tag) }}" class="tag">{{ $tag->name }}</a>
            @endforeach
        </p>
    @endif

    @if ($workout->notes || $workout->photo_path)
        <div class="card media">
            @if ($workout->photo_path)
                {{-- Private file: served by WorkoutPhotoController after a permission check --}}
                <img src="{{ route('workouts.photo', $workout) }}" alt="Progress photo" class="photo">
            @endif
            <p>{{ $workout->notes }}</p>
        </div>
    @endif

    <div class="grid">
        <x-stat label="Sets" :value="$workout->sets->count()" />
        <x-stat label="Reps" :value="$workout->sets->sum('reps')" />
        <x-stat label="Lifted" :value="App\Support\Weight::format($totalVolume, decimals: 0)" />
    </div>

    <div class="card">
        <div class="page-header">
            <h2 style="margin: 0">Sets</h2>
            <div class="actions">
                <a href="{{ route('workouts.sets.index', $workout) }}" class="btn btn-sm">Per exercise</a>
                <a href="{{ route('workouts.sets.create', $workout) }}" class="btn btn-primary btn-sm">+ Add set</a>
            </div>
        </div>
        <table>
            <thead><tr><th>#</th><th>Exercise</th><th>Reps</th><th>Weight</th><th></th></tr></thead>
            <tbody>
                @forelse ($workout->sets as $set)
                    <tr>
                        <td>{{ $set->set_number }}</td>
                        <td><a href="{{ route('exercises.show', $set->exercise) }}">{{ $set->exercise->name }}</a></td>
                        <td>{{ $set->reps }}</td>
                        <td><x-weight :kg="$set->weight_kg" /></td>
                        <td class="actions">
                            <a href="{{ route('sets.edit', $set) }}" class="btn btn-sm">Edit</a>
                            <x-delete-button :action="route('sets.destroy', $set)" confirm="Delete this set?" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">No sets logged yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('workouts.index') }}">← All workouts</a>
@endsection
