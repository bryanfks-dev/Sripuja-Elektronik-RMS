<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penjualan>
 */
class PenjualanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 13),
            'pelanggan_id' => $this->faker->numberBetween(1, 50),
            'no_nota' => $this->faker->numberBetween(100000, 999999),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
