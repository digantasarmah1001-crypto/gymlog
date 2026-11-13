<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Dummy admin required by the assessment (password: "password").
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'goal' => 'Keep GymLog running',
        ]);

        $users = User::factory(10)->create()->push($admin);

        $exercises = Exercise::factory(20)->create();

        // Workout types and the muscle groups trained in them.
        $workoutTypes = [
            'Push day' => ['Chest', 'Shoulders', 'Arms'],
            'Pull day' => ['Back', 'Arms'],
            'Leg day' => ['Legs', 'Core'],
            'Upper body' => ['Chest', 'Back', 'Shoulders'],
            'Full body' => ['Full body', 'Legs', 'Back', 'Chest', 'Core'],
        ];

        // Every user logs 3-6 workouts. A workout = 2-3 matching exercises with 2-4 sets each,
        // like a real session (e.g. 3 sets of Back Squat, then 3 sets of Leg Press).
        foreach ($users as $user) {
            for ($w = 0; $w < fake()->numberBetween(3, 6); $w++) {
                $title = array_rand($workoutTypes);
                $workout = Workout::factory()->for($user)->create(['title' => $title]);

                $matching = $exercises->whereIn('muscle_group', $workoutTypes[$title]);
                $setNumber = 1;

                foreach ($matching->random(min(fake()->numberBetween(2, 3), $matching->count())) as $exercise) {
                    $weight = $this->typicalWeight($exercise);

                    for ($i = 0; $i < fake()->numberBetween(2, 4); $i++) {
                        WorkoutSet::factory()
                            ->for($workout)
                            ->for($exercise)
                            ->create(['set_number' => $setNumber++, 'weight_kg' => $weight]);
                    }
                }
            }
        }
    }

    /**
     * A believable working weight for an exercise, based on its equipment (steps of 2.5 kg).
     */
    private function typicalWeight(Exercise $exercise): float
    {
        [$min, $max] = match ($exercise->equipment) {
            'Bodyweight' => [0, 0],
            'Dumbbells', 'Kettlebell' => [8, 40],
            'Cable', 'EZ bar' => [15, 70],
            'Machine' => [30, 160],
            default => [40, 140], // Barbell
        };

        return fake()->numberBetween($min / 2.5, $max / 2.5) * 2.5;
    }
}
