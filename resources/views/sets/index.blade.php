@extends('layouts.app')

@section('title', 'Sets of ' . $workout->title)

@section('content')
    <div class="page-header">
        <div>
            <h1>Sets of {{ $workout->title }}</h1>
            <span class="muted">{{ $workout->user->name }} · {{ $workout->date->format('j M Y') }}</span>
        </div>
        <a href="{{ route('workouts.sets.create', $workout) }}" class="btn btn-primary">+ Add set</a>
    </div>

    @forelse ($setsByExercise as $exerciseName => $sets)
        <div class="card">
            <h2>{{ $exerciseName }}</h2>
            <p>
                @foreach ($sets as $set)
                    <a href="{{ route('sets.show', $set) }}" class="btn btn-sm">{{ $set->reps }} × <x-weight :kg="$set->weight_kg" /></a>
                @endforeach
            </p>
            <p class="muted">Best set: <x-weight :kg="$sets->max('weight_kg')" /> · Total reps: {{ $sets->sum('reps') }}</p>
        </div>
    @empty
        <div class="card muted">No sets logged yet.</div>
    @endforelse

    <a href="{{ route('workouts.show', $workout) }}">← Back to workout</a>
@endsection
