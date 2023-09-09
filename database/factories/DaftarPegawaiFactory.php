<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DaftarPegawai>
 */
class DaftarPegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $pegawaiId = DB::table('pegawai')->pluck('id_pegawai');
        $jabatanId = DB::table('jabatan')->pluck('id_jabatan');
        $unitKerjaId = DB::table('unit_kerja')->pluck('id_unit_kerja');

        return [
            'nip' => fake()->randomNumber(9, true),
            'id_pegawai' => fake()->unique()->randomElement($pegawaiId),
            'id_jabatan' => fake()->randomElement($jabatanId),
            'id_unit_kerja' => fake()->randomElement($unitKerjaId),
            'no_npwp' => fake()->bothify('##.###.###.#-###.###'),
            'foto_pegawai' => null,
        ];
    }
}
