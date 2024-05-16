<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembelian>
 */
class PembelianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'supplier_id' => $this->faker->numberBetween(1, 10),
            'no_nota' => $this->faker->numberBetween(100000, 999999),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'jatuh_tempo' =>$this->faker->dateTimeBetween('now', '+2 months'),
            'no_faktur' => $this->faker->numberBetween(100000, 999999),
            'status' => $this->faker->randomElement(['Lunas', 'Belum Lunas']),
        ];
    }
}
