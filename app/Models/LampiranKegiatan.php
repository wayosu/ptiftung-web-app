<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LampiranKegiatan extends Model
{
    use HasFactory;

    protected $table = 'lampiran_kegiatans';

    protected $fillable = [
        'kegiatan_id', 
        'keterangan_lampiran',
        'status',
        'catatan_dosen',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function fileLampiranKegiatans()
    {
        return $this->hasMany(FileLampiranKegiatan::class, 'lampiran_kegiatan_id', 'id');
    }
}
