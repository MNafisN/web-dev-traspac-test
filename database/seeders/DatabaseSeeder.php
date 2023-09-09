<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(5)->create();
        \App\Models\Pegawai::factory(10)->create();
        \App\Models\Jabatan::factory(100)->create();
        \App\Models\UnitKerja::factory(200)->create();
        \App\Models\DaftarPegawai::factory(10)->create();
    }
}
