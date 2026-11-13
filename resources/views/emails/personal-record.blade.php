<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #141414; background: #f3f1ec; padding: 24px;">
    <div style="max-width: 520px; margin: 0 auto; background: #ffffff; padding: 28px; border-top: 6px solid #c8f03c;">
        <h1 style="margin-top: 0;">New personal record! 🏆</h1>
        <p>
            Nice work, {{ $set->workout->user->name }}: <strong>{{ $set->exercise->name }}</strong>,
            {{ $set->reps }} reps with <strong>{{ $set->weight_kg + 0 }} kg</strong>, your heaviest ever.
        </p>
        <p><a href="{{ route('workouts.show', $set->workout) }}" style="background: #c8f03c; color: #141414; padding: 10px 16px; text-decoration: none; font-weight: bold;">See the workout</a></p>
    </div>
</body>
</html>
