<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keterlambatan extends Model
{
    use HasFactory;

    protected $table = 'keterlambatans';

    protected $fillable = [
        'walas_id',
        'siswas_id',
        'kelas',
        'tanggal',
        'jam_masuk',
        'menit_terlambat',
        'alasan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationship ke Walas (teacher)
    public function walas()
    {
        return $this->belongsTo(Walas::class, 'walas_id');
    }

    // Relationship ke Siswa (student)
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswas_id');
    }
}
