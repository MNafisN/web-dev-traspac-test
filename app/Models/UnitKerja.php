<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\DaftarPegawai;

class UnitKerja extends Model
{
    use HasFactory;
    
    protected $table = 'unit_kerja';
    protected $primaryKey = 'id_unit_kerja';

    protected $fillable = [
        'nama_unit_kerja',
        'daerah_unit_kerja',
    ];

    public function daftarPegawai(): HasMany
    {
        return $this->hasMany(DaftarPegawai::class, 'id_unit_kerja', 'id_unit_kerja');
    }
}
