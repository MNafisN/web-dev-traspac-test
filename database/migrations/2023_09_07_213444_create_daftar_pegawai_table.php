<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_pegawai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nip')->unique();
            $table->unsignedBigInteger('id_pegawai');
            $table->unsignedBigInteger('id_jabatan');
            $table->unsignedBigInteger('id_unit_kerja');
            $table->string('no_npwp');
            $table->binary('foto_pegawai')->nullable();
            $table->timestamps();
        });

        Schema::table('daftar_pegawai', function (Blueprint $table) {
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai')->onDelete('CASCADE');
            $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatan');
            $table->foreign('id_unit_kerja')->references('id_unit_kerja')->on('unit_kerja');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daftar_pegawai');
    }
};
