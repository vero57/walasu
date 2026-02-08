<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatatanKasusSiswa extends Model
{
    use HasFactory;
    protected $fillable = [
            'walas_id',
            'siswas_id',
            'tanggal',
            'kasus',
            'tindak_lanjut',
            'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswas_id', 'id');
    }

    public function walas()
    {
        return $this->belongsTo(Walas::class, 'walas_id', 'id');
    }
}