@extends('layouts.app')

@section('title', 'Edit ' . $exercise->name)

@section('content')
    <h1>Edit {{ $exercise->name }}</h1>

    <form method="POST" action="{{ route('exercises.update', $exercise) }}" class="card form" novalidate>
        @csrf
        @method('PUT')
        @include('exercises._form')
        <div class="actions">
            <button type="submit" class="btn btn-primary">Update exercise</button>
            <a href="{{ route('exercises.show', $exercise) }}" class="btn">Cancel</a>
        </div>
    </form>
@endsection
