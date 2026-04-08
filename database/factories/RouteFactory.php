<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Route>
 */
class RouteFactory extends Factory
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
            'name' => 'Ruta ' . $this->faker->unique()->numberBetween(1, 999),
            'zone' => $this->faker->city(),
            'code' => strtoupper($this->faker->unique()->bothify('R-###??')),
        ];
    }
}
