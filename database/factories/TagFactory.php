<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'PR', 'Heavy', 'Light', 'Deload', 'Morning', 'Evening',
                'Home gym', 'Travel', 'With a friend', 'Felt great', 'Tired',
            ]),
        ];
    }
}
