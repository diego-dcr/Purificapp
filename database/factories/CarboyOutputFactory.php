<?php

namespace Database\Factories;

use App\Models\CarboyOutput;
use App\Models\Output;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CarboyOutput>
 */
class CarboyOutputFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'output_id' => Output::factory(),
            'carboy_codebar' => (string) $this->faker->numberBetween(100000, 999999),
            'timestamp' => $this->faker->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
