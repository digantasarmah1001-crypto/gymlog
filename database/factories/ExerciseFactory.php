<?php

namespace Database\Factories;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Realistic exercises: name => [muscle group, equipment].
     */
    private const EXERCISES = [
        'Bench Press' => ['Chest', 'Barbell'],
        'Incline Dumbbell Press' => ['Chest', 'Dumbbells'],
        'Push-up' => ['Chest', 'Bodyweight'],
        'Cable Fly' => ['Chest', 'Cable'],
        'Deadlift' => ['Back', 'Barbell'],
        'Pull-up' => ['Back', 'Bodyweight'],
        'Barbell Row' => ['Back', 'Barbell'],
        'Lat Pulldown' => ['Back', 'Cable'],
        'Seated Cable Row' => ['Back', 'Cable'],
        'Back Squat' => ['Legs', 'Barbell'],
        'Front Squat' => ['Legs', 'Barbell'],
        'Leg Press' => ['Legs', 'Machine'],
        'Romanian Deadlift' => ['Legs', 'Barbell'],
        'Walking Lunge' => ['Legs', 'Dumbbells'],
        'Leg Curl' => ['Legs', 'Machine'],
        'Calf Raise' => ['Legs', 'Machine'],
        'Overhead Press' => ['Shoulders', 'Barbell'],
        'Lateral Raise' => ['Shoulders', 'Dumbbells'],
        'Face Pull' => ['Shoulders', 'Cable'],
        'Barbell Curl' => ['Arms', 'Barbell'],
        'Hammer Curl' => ['Arms', 'Dumbbells'],
        'Tricep Pushdown' => ['Arms', 'Cable'],
        'Skull Crusher' => ['Arms', 'EZ bar'],
        'Dip' => ['Arms', 'Bodyweight'],
        'Plank' => ['Core', 'Bodyweight'],
        'Hanging Leg Raise' => ['Core', 'Bodyweight'],
        'Cable Crunch' => ['Core', 'Cable'],
        'Kettlebell Swing' => ['Full body', 'Kettlebell'],
        'Clean and Press' => ['Full body', 'Barbell'],
        'Burpee' => ['Full body', 'Bodyweight'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(array_keys(self::EXERCISES));
        [$muscleGroup, $equipment] = self::EXERCISES[$name];

        return [
            'name' => $name,
            'muscle_group' => $muscleGroup,
            'equipment' => $equipment,
            'description' => fake()->sentence(12),
        ];
    }
}
