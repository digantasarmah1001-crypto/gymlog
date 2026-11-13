@extends('layouts.app')

@section('title', 'Add set')

@section('content')
    <h1>Add a set</h1>
    <p class="muted">To <a href="{{ route('workouts.show', $workout) }}">{{ $workout->title }}</a> on {{ $workout->date->format('j M Y') }}</p>

    <form method="POST" action="{{ route('workouts.sets.store', $workout) }}" class="card form" novalidate>
        @csrf
        @include('sets._form')
        <div class="actions">
            <button type="submit" class="btn btn-primary">Save set</button>
            <a href="{{ route('workouts.show', $workout) }}" class="btn">Cancel</a>
        </div>
    </form>
@endsection
