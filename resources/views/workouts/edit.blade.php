@extends('layouts.app')

@section('title', 'Edit ' . $workout->title)

@section('content')
    <h1>Edit workout</h1>

    <form method="POST" action="{{ route('workouts.update', $workout) }}" class="card form" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        @include('workouts._form')
        <div class="actions">
            <button type="submit" class="btn btn-primary">Update workout</button>
            <a href="{{ route('workouts.show', $workout) }}" class="btn">Cancel</a>
        </div>
    </form>
@endsection
