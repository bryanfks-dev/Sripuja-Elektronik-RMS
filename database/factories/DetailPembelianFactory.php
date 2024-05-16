<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPenjualan>
 */
class DetailPembelianFactory extends Factory
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
            'barang_id' => $this->faker->numberBetween(1, 25),
            'pembelian_id' => $this->faker->numberBetween(1, 169),
            'jumlah' => $this->faker->numberBetween(1, 100),
            'sub_total' => $this->faker->numberBetween(10000, 100000),
        ];
    }
}
