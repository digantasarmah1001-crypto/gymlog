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
        ]);

        $users = User::factory(10)->create()->push($admin);

        $exercises = Exercise::factory(20)->create();

        // Every user logs 3-6 workouts; every workout has 3-6 sets of existing exercises.
        foreach ($users as $user) {
            Workout::factory(fake()->numberBetween(3, 6))
                ->for($user)
                ->create()
                ->each(function (Workout $workout) use ($exercises) {
                    $setCount = fake()->numberBetween(3, 6);

                    for ($setNumber = 1; $setNumber <= $setCount; $setNumber++) {
                        WorkoutSet::factory()
                            ->for($workout)
                            ->for($exercises->random())
                            ->create(['set_number' => $setNumber]);
                    }
                });
        }
    }
}
