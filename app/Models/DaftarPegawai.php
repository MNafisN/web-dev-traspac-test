<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\UnitKerja;

class DaftarPegawai extends Model
{
    use HasFactory;

    protected $table = 'daftar_pegawai';
    protected $primaryKey = 'nip';

    protected $fillable = [
        'no_npwp',
        'id_pegawai',
        'id_jabatan',
        'id_unit_kerja',
        'no_npwp',
        'foto_pegawai',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_kerja', 'id_unit_kerja');
    }
}
