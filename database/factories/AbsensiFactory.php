<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
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
            'karyawan_id' => $this->faker->numberBetween(2, 12),
            'tanggal_waktu' => $this->faker->dateTimeInInterval('-1 week', '+3 days'),

        ];
    }
}
