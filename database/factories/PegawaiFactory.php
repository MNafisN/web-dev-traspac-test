<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'id_pegawai' => fake()->unique()->,
            'nama' => fake()->name(),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTime('01-01-2000')->format('Y-m-d'),
            'jenis_kelamin' => fake()->randomElement(['P', 'L']),
            'agama' => fake()->randomElement(['Islam', 'Kristen', 'Hindu', 'Buddha']),
            'alamat' => fake()->streetAddress(),
            'no_telepon' => fake()->phoneNumber(),
        ];
    }
}
