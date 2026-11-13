<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Workout>
 */
class WorkoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement([
                'Push day', 'Pull day', 'Leg day', 'Upper body', 'Lower body',
                'Full body', 'Chest & triceps', 'Back & biceps', 'Shoulders & core',
            ]),
            'date' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'duration_minutes' => fake()->numberBetween(30, 120),
            'notes' => fake()->optional(0.5)->sentence(),
        ];
    }
}
