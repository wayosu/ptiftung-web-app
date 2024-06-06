<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatans';

    protected $fillable = [
        'kegiatan_mahasiswa_id',
        'dosen_id',
        'mahasiswa_id',
    ];

    public function lampiranKegiatans()
    {
        return $this->hasMany(LampiranKegiatan::class, 'kegiatan_id');
    }

    public function kegiatanMahasiswa()
    {
        return $this->belongsTo(KegiatanMahasiswa::class, 'kegiatan_mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
