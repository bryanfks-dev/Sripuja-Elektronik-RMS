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
            'kode_barang' => $this->faker->unique()->ean8(),
            'nama_barang' => $this->faker->name(),
            'merek_barang_id' => $this->faker->numberBetween(1, 10),
            'jenis_barang_id' => $this->faker->numberBetween(1, 10),
            'jumlah_per_grosir' => $this->faker->numberBetween(1, 100),
        ];
    }
}
