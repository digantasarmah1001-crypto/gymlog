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
            @auth
                <div class="page-header" style="margin-bottom: 8px">
                    <h2 style="margin: 0">Your latest workouts</h2>
                    <a href="{{ route('workouts.create') }}" class="btn btn-primary btn-sm">+ Log workout</a>
                </div>
                <ul class="list">
                    @forelse ($latestWorkouts as $workout)
                        <li>
                            <a href="{{ route('workouts.show', $workout) }}"><strong>{{ $workout->title }}</strong></a>
                            <span class="muted">{{ $workout->date->format('D j M') }} · {{ $workout->sets_count }} sets</span>
                        </li>
                    @empty
                        <li class="muted">No workouts yet. Log your first one!</li>
                    @endforelse
                </ul>
            @else
                <h2>Start logging</h2>
                <p>Create a free account to log your workouts, track every set and see your personal bests.</p>
                <p class="actions">
                    <a href="{{ route('register') }}" class="btn btn-primary">Create account</a>
                    <a href="{{ route('login') }}" class="btn">Log in</a>
                </p>
            @endauth
        </section>

        <section class="card">
            <h2>Exercise library</h2>
            @foreach ($exercisesByMuscle as $muscleGroup => $exercises)
                <p>
                    <span class="badge">{{ $muscleGroup }}</span><br>
                    @foreach ($exercises as $exercise)
                        <a href="{{ route('exercises.show', $exercise) }}">{{ $exercise->name }}</a>@if (! $loop->last), @endif
                    @endforeach
                </p>
            @endforeach
        </section>
    </div>
@endsection
