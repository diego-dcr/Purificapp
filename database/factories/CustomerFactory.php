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
            'barcode' => $this->faker->unique()->numerify('7############'),
            'number' => $this->faker->unique()->numerify('C####'),
            'name' => $this->faker->name(),
        ];
    }
}
