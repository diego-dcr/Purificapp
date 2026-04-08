<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'route_id' => Route::factory(),
            'barcode' => fake()->unique()->numerify('7############'),
            'number' => fake()->unique()->numerify('C####'),
            'name' => fake()->name(),
        ];
    }
}
