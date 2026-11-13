@extends('layouts.app')

@section('title', 'Log workout')

@section('content')
    <h1>Log a workout</h1>

    <form method="POST" action="{{ route('workouts.store') }}" class="card form" novalidate>
        @csrf
        @include('workouts._form')
        <div class="actions">
            <button type="submit" class="btn btn-primary">Save workout</button>
            <a href="{{ route('workouts.index') }}" class="btn">Cancel</a>
        </div>
    </form>
@endsection
