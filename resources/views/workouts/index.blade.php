@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
    <div class="page-header">
        <h1>Workouts</h1>
        <div class="actions">
            <form method="GET" action="{{ route('workouts.index') }}" class="actions">
                <select name="user" class="filter" onchange="this.form.submit()">
                    <option value="">All athletes</option>
                    @foreach ($athletes as $id => $name)
                        <option value="{{ $id }}" @selected(request('user') == $id)>{{ $name }}</option>
                    @endforeach
                </select>
                <noscript><button type="submit" class="btn btn-sm">Filter</button></noscript>
            </form>
            <a href="{{ route('workouts.create') }}" class="btn btn-primary">+ Log workout</a>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr><th>Date</th><th>Workout</th><th>Athlete</th><th>Duration</th><th>Sets</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($workouts as $workout)
                    <tr>
                        <td>{{ $workout->date->format('D j M Y') }}</td>
                        <td><a href="{{ route('workouts.show', $workout) }}">{{ $workout->title }}</a></td>
                        <td>{{ $workout->user->name }}</td>
                        <td>{{ $workout->duration_minutes ? $workout->duration_minutes . ' min' : '—' }}</td>
                        <td>{{ $workout->sets_count }}</td>
                        <td class="actions">
                            <a href="{{ route('workouts.edit', $workout) }}" class="btn btn-sm">Edit</a>
                            <x-delete-button :action="route('workouts.destroy', $workout)"
                                             confirm="Delete this workout and all of its sets?" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">No workouts logged yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $workouts->links() }}
    </div>
@endsection
