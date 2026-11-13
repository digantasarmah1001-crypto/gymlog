@extends('layouts.app')

@section('title', $tag->name)

@section('content')
    <div class="page-header">
        <div>
            <h1><span class="tag tag-lg">{{ $tag->name }}</span></h1>
            <span class="muted">{{ auth()->user()->is_admin ? 'All workouts' : 'Your workouts' }} with this tag</span>
        </div>
        @can('update', $tag)
            <div class="actions">
                <a href="{{ route('tags.edit', $tag) }}" class="btn">Rename</a>
                <x-delete-button :action="route('tags.destroy', $tag)" confirm="Delete this tag? Workouts keep existing." />
            </div>
        @endcan
    </div>

    <div class="card">
        <ul class="list">
            @forelse ($workouts as $workout)
                <li>
                    <a href="{{ route('workouts.show', $workout) }}"><strong>{{ $workout->title }}</strong></a>
                    <span class="muted">{{ $workout->user->name }} · {{ $workout->date->format('D j M Y') }}</span>
                </li>
            @empty
                <li class="muted">No workouts with this tag yet.</li>
            @endforelse
        </ul>
    </div>

    <a href="{{ route('tags.index') }}">← All tags</a>
@endsection
