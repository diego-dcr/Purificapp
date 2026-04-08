<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\Customer;
use App\Models\Route;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
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
            'route_id' => Route::factory(),
            'customer_id' => Customer::factory(),
            'cost' => $this->faker->randomFloat(2, 30, 1500),
            'concept_id' => Concept::query()
                ->where('type', Concept::TYPE_INCOME)
                ->inRandomOrder()
                ->value('id')
                ?? Concept::factory()->create(['type' => Concept::TYPE_INCOME])->id,
            'created_by' => User::factory(),
            'external_id' => $this->faker->unique()->uuid(),
            'latitude' => (string) $this->faker->latitude(18.5, 22.5),
            'longitude' => (string) $this->faker->longitude(-106.0, -97.0),
            'timestamp' => $this->faker->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
