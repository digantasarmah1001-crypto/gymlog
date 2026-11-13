@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <section class="hero">
        <h1>Log every rep. <mark>Beat it</mark> next time.</h1>
        <p>
            GymLog is a simple workout tracker. A workout holds the exercises you did,
            and for every exercise you log your sets: reps × kg.
        </p>
    </section>

    <section class="grid">
        <x-stat label="Workouts logged" :value="number_format($workoutCount)" />
        <x-stat label="Sets completed" :value="number_format($setCount)" />
        <x-stat label="Kg lifted" :value="number_format($kgLifted)" />
        <x-stat label="Exercises" :value="$exerciseCount" />
    </section>

    <div class="split">
        <section class="card">
            <h2>Latest workouts</h2>
            <ul class="list">
                @forelse ($latestWorkouts as $workout)
                    <li>
                        <span><strong>{{ $workout->title }}</strong> <span class="muted">· {{ $workout->user->name }}</span></span>
                        <span class="muted">{{ $workout->date->format('D j M') }} · {{ $workout->sets_count }} sets</span>
                    </li>
                @empty
                    <li class="muted">No workouts logged yet.</li>
                @endforelse
            </ul>
        </section>

        <section class="card">
            <h2>Exercise library</h2>
            @foreach ($exercisesByMuscle as $muscleGroup => $exercises)
                <p>
                    <span class="badge">{{ $muscleGroup }}</span><br>
                    {{ $exercises->pluck('name')->join(', ') }}
                </p>
            @endforeach
        </section>
    </div>
@endsection
