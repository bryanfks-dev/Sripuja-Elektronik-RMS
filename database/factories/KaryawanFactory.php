<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Karyawan>
 */
class KaryawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $namaLengkap = $this->faker->name();
        $noHp = $this->faker->numberBetween(62800000000, 6289999999999);

        return [
            //
            'nama_lengkap' => $namaLengkap,
            'no_hp' => $noHp,
            'user_id' => User::factory()
                ->withNamaLengkap($namaLengkap)
                ->withNoHp($noHp),

            'alamat' => $this->faker->address(),
            'telepon' => $this->faker->phoneNumber(),

            'gaji' => $this->faker->numberBetween(10000, 100000),
            'gaji_bln_ini' => $this->faker->numberBetween(10000, 100000),
            'tipe' => $this->faker->randomElement(['Kasir', 'Non-Kasir']),

        ];
    }
}
