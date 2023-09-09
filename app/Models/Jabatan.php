<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\DaftarPegawai;

class Jabatan extends Model
{
    use HasFactory;
    
    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';

    protected $fillable = [
        'nama_jabatan',
        'golongan',
        'eselon',
    ];

    public function daftarPegawai(): HasMany
    {
        return $this->hasMany(DaftarPegawai::class, 'id_jabatan', 'id_jabatan');
    }
}
