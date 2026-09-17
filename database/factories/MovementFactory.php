<?php

namespace Database\Factories;

use App\Models\Movement;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Movement>
 */
class MovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'type' => 'entrada',
            'quantity' => fake()->numberBetween(1, 20),
            'supplier' => fake()->company(),
            'reason' => null,
            'moved_at' => now(),
        ];
    }
}
