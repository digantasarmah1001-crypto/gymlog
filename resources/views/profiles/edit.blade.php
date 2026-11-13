@extends('layouts.app')

@section('title', 'Edit profile')

@section('content')
    <h1>Edit your profile</h1>
    <p class="muted">Your email and bodyweight are private: only you can see them.</p>

    <form method="POST" action="{{ route('profile.update') }}" class="card form" novalidate>
        @csrf
        @method('PUT')
        <x-input name="name" label="Name" :value="$athlete->name" required maxlength="100" />
        <x-input name="goal" label="Goal (public, optional)" :value="$athlete->goal" maxlength="120" placeholder="e.g. Squat 140 kg" />
        <x-input name="bodyweight_kg" label="Bodyweight in kg (private, optional)" type="number" step="0.1" min="30" max="300"
                 :value="$athlete->bodyweight_kg" />
        <div class="actions">
            <button type="submit" class="btn btn-primary">Save profile</button>
            <a href="{{ route('profiles.show', $athlete) }}" class="btn">Cancel</a>
        </div>
    </form>
@endsection
