<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Pegawai;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $primaryKey = 'file_id';

    protected $fillable = [
        'file_name',
        'id_pegawai',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}
