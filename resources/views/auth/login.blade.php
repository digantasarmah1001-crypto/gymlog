@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <div class="auth">
        <h1>Log in</h1>
        <p class="muted">No account yet? <a href="{{ route('register') }}">Register here</a>.</p>

        <form method="POST" action="{{ route('login') }}" class="card form" novalidate>
            @csrf
            <x-input name="email" label="Email" type="email" required autofocus autocomplete="email" />
            <x-input name="password" label="Password" type="password" required autocomplete="current-password" />
            <div class="field">
                <label class="check"><input type="checkbox" name="remember" value="1"> Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Log in</button>
        </form>
    </div>
@endsection
