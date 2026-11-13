@extends('layouts.app')

@section('title', 'Rename tag')

@section('content')
    <h1>Rename tag</h1>

    <form method="POST" action="{{ route('tags.update', $tag) }}" class="card form" novalidate>
        @csrf
        @method('PUT')
        <x-input name="name" label="Name" :value="$tag->name" required maxlength="40" placeholder="e.g. Deload" />
        <div class="actions">
            <button type="submit" class="btn btn-primary">Update tag</button>
            <a href="{{ route('tags.index') }}" class="btn">Cancel</a>
        </div>
    </form>
@endsection
