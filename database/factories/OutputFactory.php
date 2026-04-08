<?php

namespace Database\Factories;

use App\Models\Output;
use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Output>
 */
class OutputFactory extends Factory
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
            'created_by' => User::factory(),
            'timestamp' => $this->faker->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
