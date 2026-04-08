<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\Customer;
use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Income>
 */
class IncomeFactory extends Factory
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
                ->where('type', Concept::TYPE_INCOME)
                ->inRandomOrder()
                ->value('id')
                ?? Concept::factory()->create(['type' => Concept::TYPE_INCOME])->id,
            'customer_id' => $this->faker->boolean(60) ? Customer::query()->inRandomOrder()->value('id') : null,
            'user_id' => $this->faker->boolean(70) ? User::query()->inRandomOrder()->value('id') : null,
            'amount' => $this->faker->randomFloat(2, 20, 5000),
            'description' => $this->faker->boolean(70) ? $this->faker->sentence(4) : null,
            'created_by' => User::factory(),
            'timestamp' => $this->faker->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
