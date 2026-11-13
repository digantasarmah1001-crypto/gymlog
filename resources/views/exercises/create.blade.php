@extends('layouts.app')

@section('title', 'New exercise')

@section('content')
    <h1>New exercise</h1>

    <form method="POST" action="{{ route('exercises.store') }}" class="card form" novalidate>
        @csrf
        @include('exercises._form')
        <div class="actions">
            <button type="submit" class="btn btn-primary">Save exercise</button>
            <a href="{{ route('exercises.index') }}" class="btn">Cancel</a>
        </div>
    </form>
@endsection
