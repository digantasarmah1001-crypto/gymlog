@extends('layouts.app')

@section('title', 'Athletes')

@section('content')
    <div class="page-header">
        <h1>Athletes</h1>
    </div>

    <div class="card">
        <table>
            <thead><tr><th>Athlete</th><th>Goal</th><th>Workouts</th></tr></thead>
            <tbody>
                @foreach ($athletes as $athlete)
                    <tr>
                        <td><a href="{{ route('profiles.show', $athlete) }}">{{ $athlete->name }}</a></td>
                        <td class="muted">{{ $athlete->goal ?? '—' }}</td>
                        <td class="num">{{ $athlete->workouts_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
