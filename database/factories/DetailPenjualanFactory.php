<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPenjualan>
 */
class DetailPenjualanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = $this->faker->numberBetween(10000, 100000);
        $jumlah = $this->faker->numberBetween(1, 100);

        return [
            'barang_id' => $this->faker->numberBetween(1, 25),
            'penjualan_id' => $this->faker->numberBetween(1, 100),
            'jumlah' => json_encode([
                (string) $this->faker->numberBetween(1, 20) => $jumlah,
            ]),
            'harga_jual' => $total,
            'sub_total' => $total * $jumlah,
        ];
    }
}
