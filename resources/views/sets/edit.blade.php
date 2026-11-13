@extends('layouts.app')

@section('title', 'Edit set')

@section('content')
    <h1>Edit set {{ $set->set_number }}</h1>
    <p class="muted">Of <a href="{{ route('workouts.show', $workout) }}">{{ $workout->title }}</a> on {{ $workout->date->format('j M Y') }}</p>

    <form method="POST" action="{{ route('sets.update', $set) }}" class="card form" novalidate>
        @csrf
        @method('PUT')
        @include('sets._form')
        <div class="actions">
            <button type="submit" class="btn btn-primary">Update set</button>
            <a href="{{ route('workouts.show', $workout) }}" class="btn">Cancel</a>
        </div>
    </form>
@endsection
