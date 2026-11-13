{{--
    Minimal layout for error pages. It deliberately does NOT use auth(), the session or the database:
    if one of those is what broke (a 500 error), the error page itself must still work.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') · GymLog</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav class="nav">
        <div class="container"><a href="{{ url('/') }}" class="brand">Gym<span>Log</span></a></div>
    </nav>
    <main>
        <div class="container error-page">
            <div class="error-code">@yield('code')</div>
            <h1>@yield('title')</h1>
            <p class="muted">@yield('message')</p>
            <p class="actions">
                <a href="{{ url('/') }}" class="btn btn-primary">Back to the start</a>
                <a href="javascript:history.back()" class="btn">Go back</a>
            </p>
        </div>
    </main>
</body>
</html>
