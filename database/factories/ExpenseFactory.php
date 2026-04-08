<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'concept_id' => Concept::query()
                ->where('type', Concept::TYPE_EXPENSE)
                ->inRandomOrder()
                ->value('id')
                ?? Concept::factory()->create(['type' => Concept::TYPE_EXPENSE])->id,
            'amount' => $this->faker->randomFloat(2, 20, 3000),
            'description' => $this->faker->boolean(75) ? $this->faker->sentence(5) : null,
            'created_by' => User::factory(),
            'timestamp' => $this->faker->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
