<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
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
            'kode_barang' => $this->faker->unique()->ean8(),
            'nama_barang' => $this->faker->name(),
            'stock' => $this->faker->numberBetween(1, 100),
            'harga_jual' => $this->faker->numberBetween(10000, 100000),
            'harga_beli' => $this->faker->numberBetween(10000, 100000),
            'harga_grosir' => $this->faker->numberBetween(10000, 100000),
        ];
    }
}
