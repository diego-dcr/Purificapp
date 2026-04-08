<?php

namespace Database\Factories;

use App\Models\Concept;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Concept>
 */
class ConceptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([
            Concept::TYPE_INCOME,
            Concept::TYPE_EXPENSE,
        ]);

        return [
            'name' => fake()->words(2, true),
            'code' => (string) fake()->unique()->numberBetween(101, 9999),
            'type' => $type,
            'allows_carboy' => fake()->boolean(20),
        ];
    }
}
