{{-- The frame around every page: nav, messages, content, footer. Pages fill @yield('content'). --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Track your training') · GymLog</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav class="nav">
        <div class="container">
            <a href="{{ route('home') }}" class="brand">Gym<span>Log</span></a>
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
