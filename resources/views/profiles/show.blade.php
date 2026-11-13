@extends('layouts.app')

@section('title', $athlete->name)

@section('content')
    {{-- Privacy by design: email and bodyweight are NOT shown on a public profile. --}}
    <div class="page-header">
        <div>
            <h1>{{ $athlete->name }}</h1>
            @if ($athlete->goal)
                <span class="badge">Goal</span> {{ $athlete->goal }}
            @endif
        </div>
    </div>

    <section class="grid">
        <x-stat label="Workouts" :value="$totalWorkouts" />
        <x-stat label="This month" :value="$workoutsThisMonth" />
        <x-stat label="Sets" :value="$totalSets" />
        <x-stat label="Favourite" :value="$favouriteExercise ?? '—'" />
    </section>

    <div class="split">
        <section class="card">
            <h2>Personal bests</h2>
            <p class="muted">Calculated by the database (Eloquent: <code>MAX</code> + <code>GROUP BY</code>).</p>
            <table>
                <thead><tr><th>Exercise</th><th>Best</th><th>Sets logged</th></tr></thead>
                <tbody>
                    @forelse ($personalBests as $best)
                        <tr>
                            <td><a href="{{ route('exercises.show', $best->exercise) }}">{{ $best->exercise->name }}</a></td>
                            <td class="num"><strong>{{ $best->best_kg }} kg</strong></td>
                            <td class="num">{{ $best->times }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="muted">No sets logged yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="card">
            <h2>Volume per muscle</h2>
            <p class="muted">Calculated in PHP (Collection: <code>groupBy</code> + <code>sum</code>).</p>
            @php($maxVolume = $volumeByMuscle->max() ?: 1)
            @forelse ($volumeByMuscle as $muscle => $volume)
                <div class="bar-row">
                    <span>{{ $muscle }}</span>
                    <div class="bar"><div style="width: {{ round($volume / $maxVolume * 100) }}%"></div></div>
                    <span class="num">{{ number_format($volume) }} kg</span>
                </div>
            @empty
                <p class="muted">Nothing yet.</p>
            @endforelse
        </section>
    </div>

    <section class="card">
        <h2>Recent workouts</h2>
        <ul class="list">
            @forelse ($recentWorkouts as $workout)
                <li>
                    <a href="{{ route('workouts.show', $workout) }}"><strong>{{ $workout->title }}</strong></a>
                    <span class="muted">{{ $workout->date->format('D j M') }} · {{ $workout->sets_count }} sets</span>
                </li>
            @empty
                <li class="muted">No workouts yet.</li>
            @endforelse
        </ul>
    </section>

    <a href="{{ route('profiles.index') }}">← All athletes</a>
@endsection
