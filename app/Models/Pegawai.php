<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\DaftarPegawai;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_telepon',
    ];

    protected $casts = [
        'tanggal_lahir' => 'datetime:Y-m-d',
    ];

    public function daftarPegawai(): HasOne
    {
        return $this->hasOne(DaftarPegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function photo(): HasOne
    {
        return $this->hasOne(File::class, 'id_pegawai', 'id_pegawai');
    }
}
