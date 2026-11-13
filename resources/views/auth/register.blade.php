@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="auth">
        <h1>Join GymLog</h1>
        <p class="muted">Already have an account? <a href="{{ route('login') }}">Log in</a>.</p>

        <form method="POST" action="{{ route('register') }}" class="card form" novalidate>
            @csrf
            <x-input name="name" label="Name" required autofocus autocomplete="name" />
            <x-input name="email" label="Email" type="email" required autocomplete="email" />
            <x-input name="password" label="Password (min. 8 characters)" type="password" required autocomplete="new-password" />
            <x-input name="password_confirmation" label="Repeat password" type="password" required autocomplete="new-password" />
            <button type="submit" class="btn btn-primary">Create account</button>
        </form>
    </div>
@endsection
