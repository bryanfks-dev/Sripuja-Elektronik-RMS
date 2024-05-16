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
            'tanggal_waktu' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'tanggal_jatuh_tempo' =>$this->faker->dateTimeBetween('now', '+2 months'),
            'status' => $this->faker->randomElement(['Lunas', 'Belum Lunas']),
        ];
    }
}
