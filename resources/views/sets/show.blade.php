@extends('layouts.app')

@section('title', 'Set ' . $set->set_number)

@section('content')
    <div class="page-header">
        <div>
            <h1>Set {{ $set->set_number }}: {{ $set->exercise->name }}</h1>
            <span class="muted">
                <a href="{{ route('workouts.show', $set->workout) }}">{{ $set->workout->title }}</a>
                · {{ $set->workout->user->name }} · {{ $set->workout->date->format('j M Y') }}
            </span>
        </div>
        <div class="actions">
            <a href="{{ route('sets.edit', $set) }}" class="btn">Edit</a>
            <x-delete-button :action="route('sets.destroy', $set)" confirm="Delete this set?" />
        </div>
    </div>

    <div class="grid">
        <x-stat label="Reps" :value="$set->reps" />
        <x-stat label="Kg" :value="$set->weight_kg" />
        <x-stat label="Volume (kg)" :value="number_format($set->reps * $set->weight_kg)" />
    </div>

    <a href="{{ route('exercises.show', $set->exercise) }}">More about {{ $set->exercise->name }} →</a>
@endsection
