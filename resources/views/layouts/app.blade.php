{{-- The frame around every page: nav, messages, content, footer. Pages fill @yield('content'). --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Track your training') · GymLog</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- defer = download now, run after the HTML is parsed (so the elements exist). --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <nav class="nav">
        <div class="container">
            <a href="{{ route('home') }}" class="brand">Gym<span>Log</span></a>
            @auth
                <a href="{{ route('workouts.index') }}" @class(['active' => request()->routeIs('workouts.*', 'sets.*')])>My workouts</a>
                <a href="{{ route('tags.index') }}" @class(['active' => request()->routeIs('tags.*')])>Tags</a>
            @endauth
            <a href="{{ route('exercises.index') }}" @class(['active' => request()->routeIs('exercises.*')])>Exercises</a>
            <a href="{{ route('profiles.index') }}" @class(['active' => request()->routeIs('profiles.index')])>Athletes</a>

            {{-- @auth / @guest: show different links depending on whether someone is logged in --}}
            @auth
                <a href="{{ route('profiles.show', auth()->user()) }}" class="nav-user">
                    Hi, {{ auth()->user()->name }} @if (auth()->user()->is_admin)<span class="badge">Admin</span>@endif
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="nav-button">Log out</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" @class(['active' => request()->routeIs('login')])>Log in</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
            @endguest
        </div>
    </nav>

    <main>
        <div class="container">
            {{-- Green message after saving (set with ->with('status', '...') in a controller) --}}
            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            {{-- Red banner when a form came back with validation errors --}}
            @if ($errors->any())
                <div class="alert-error">Please fix the highlighted fields below.</div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer>GymLog · CS404 project</footer>
</body>
</html>
