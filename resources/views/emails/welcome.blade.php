{{-- Simple HTML email. Public properties of the Mailable ($user) are available here. --}}
<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #141414; background: #f3f1ec; padding: 24px;">
    <div style="max-width: 520px; margin: 0 auto; background: #ffffff; padding: 28px; border-top: 6px solid #c8f03c;">
        <h1 style="margin-top: 0;">Welcome, {{ $user->name }}!</h1>
        <p>Your GymLog account is ready. Here's how to get started:</p>
        <ol>
            <li>Log a workout (for example "Leg day").</li>
            <li>Add your sets: exercise, reps and weight.</li>
            <li>Check your profile to see your personal bests grow.</li>
        </ol>
        <p><a href="{{ route('workouts.create') }}" style="background: #c8f03c; color: #141414; padding: 10px 16px; text-decoration: none; font-weight: bold;">Log your first workout</a></p>
        <p style="color: #6d6a63; font-size: 13px;">You get this email because you registered at GymLog.</p>
    </div>
</body>
</html>
