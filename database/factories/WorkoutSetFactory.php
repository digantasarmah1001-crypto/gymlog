<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkoutSet>
 */
class WorkoutSetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workout_id' => Workout::factory(),
            'exercise_id' => Exercise::factory(),
            'set_number' => fake()->numberBetween(1, 5),
            'reps' => fake()->numberBetween(5, 15),
            // Real plates go up in steps of 2.5 kg: 10 kg … 150 kg.
            'weight_kg' => fake()->numberBetween(4, 60) * 2.5,
        ];
    }
}
