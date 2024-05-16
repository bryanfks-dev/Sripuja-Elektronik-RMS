<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
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
            'nama' => $this->faker->name(),
            'no_hp' => $this->faker->numberBetween(62800000000, 6289999999999),
            'alamat' => $this->faker->address(),
            'telepon' => $this->faker->phoneNumber(),
            'fax' => $this->faker->numberBetween(666, 999),
            'nama_sales' => $this->faker->name(),
            'no_hp_sales' => $this->faker->numberBetween(62800000000, 6289999999999),

        ];
    }
}
